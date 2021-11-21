<?php

namespace App\Exceptions;

use App\Exceptions\Http\HttpException;
use Exception;
use ErrorException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;

class Handler
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        error_reporting(-1);

        set_error_handler([$this, 'handleError']);

        set_exception_handler([$this, 'handleException']);

        register_shutdown_function([$this, 'handleShutdown']);

        $this->container = $container;
    }

    /**
     * Render the exception
     *
     * @param Throwable $e
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function render(Throwable $e)
    {
        if ($e instanceof HttpException) {
            return response($this->container->get(Environment::class)
                ->render("errors/{$e->getCode()}.twig", ['e' => $e]), $e->getCode());
        }

        if (env('APP_ENV') === 'dev') {
            return response($this->makeWhoops()->handleException($e), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response($this->container->get(Environment::class)
            ->render("errors/500.twig", ['e' => $e]));
    }

    /**
     * Handle exception
     *
     * @param Throwable $e
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function handleException(Throwable $e)
    {
        return $this->render($e);
    }

    /**
     * Handle an error
     *
     * @param $level
     * @param $message
     * @param string $file
     * @param int $line
     * @param array $context
     * @throws ErrorException
     */
    public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        throw new ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Handle shutdown
     *
     * @throws Exception
     */
    public function handleShutdown()
    {
        if (!is_null($error = error_get_last()) && $this->isFatal($error['type'])) {
            throw new Exception($error['message'], 0);
        }
    }

    /**
     * Determine if our exception should be considered "fatal"
     *
     * @param int $type
     * @return bool
     */
    public function isFatal(int $type)
    {
        return in_array($type, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE]);
    }

    /**
     * Create whoops handler
     *
     * @return Whoops
     */
    protected function makeWhoops()
    {
        $whoops = new Whoops();
        $whoops->allowQuit(false);
        $whoops->writeToOutput(false);
        $whoops->pushHandler(new PrettyPageHandler);

        return $whoops;
    }
}