<?php

namespace App\Http;

use App\Exceptions\Handler;
use App\Exceptions\Http\HttpMethodNotAllowedException;
use App\Exceptions\Http\HttpNotFoundException;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\DetermineUserLanguage;
use App\Http\Middleware\EnsureCorrectPathInfo;
use App\Http\Middleware\SavePreviousUrl;
use App\Http\Middleware\StartSession;
use App\Support\Pipeline;
use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Kernel
{
    protected ContainerInterface $container;

    protected Handler $handler;

    protected array $middleware = [
        EnsureCorrectPathInfo::class,
        StartSession::class,
        Authenticate::class,
        DetermineUserLanguage::class,
        SavePreviousUrl::class,
    ];

    public function __construct(ContainerInterface $container, Handler $handler)
    {
        $this->container = $container;
        $this->handler = $handler;
    }

    /**
     * Handle the incoming request
     *
     * @param Request $request
     * @param Dispatcher $dispatcher
     * @return mixed|Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function handle(Request $request, Dispatcher $dispatcher)
    {
        try {
            $response = $this->throughRouter($request, $dispatcher);
        } catch (Throwable $e) {
            $response = $this->handler->render($e);
        }

        return $response;
    }

    /**
     * Send the incoming request through router
     *
     * @param Request $request
     * @param Dispatcher $dispatcher
     * @return mixed
     */
    protected function throughRouter(Request $request, Dispatcher $dispatcher)
    {
        return (new Pipeline($this->container))
            ->send($request)
            ->through($this->middleware)
            ->then($this->dispatchToRouter($request, $dispatcher));
    }

    /**
     * Dispatch the incoming request to the router
     *
     * @param Request $request
     * @param Dispatcher $dispatcher
     * @return \Closure
     */
    protected function dispatchToRouter(Request $request, Dispatcher $dispatcher)
    {
        return function () use ($request, $dispatcher) {
            $dispatched = $dispatcher->dispatch(
                $request->getMethod(),
                $request->getPathInfo()
            );

            if ($dispatched[0] === Dispatcher::NOT_FOUND) {
                throw new HttpNotFoundException();
            }

            if ($dispatched[0] === Dispatcher::METHOD_NOT_ALLOWED) {
                throw new HttpMethodNotAllowedException();
            }

            [, $controller, $parameters] = $dispatched;

            $response = $this->container->call($controller, $parameters);

            if (!$response instanceof Response) {
                $response = new Response($response);
            }

            return $response->prepare($request);
        };
    }
}