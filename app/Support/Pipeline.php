<?php

namespace App\Support;

use Closure;
use Psr\Container\ContainerInterface;
use Throwable;

class Pipeline
{
    protected ContainerInterface $container;

    /**
     * The object/instance being passed though the pipeline
     *
     * @var mixed
     */
    protected $passable;

    /**
     * Array of pipes
     *
     * @var array
     */
    protected array $pipes = [];

    /**
     * The method to call on each instance
     *
     * @var string
     */
    protected string $method = 'handle';

    /**
     * Pipeline constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Set the object/instance being passed
     *
     * @param mixed $passable
     * @return Pipeline
     */
    public function send($passable)
    {
        $this->passable = $passable;

        return $this;
    }

    /**
     * Set array of pipes
     *
     * @param mixed $pipes
     * @return Pipeline
     */
    public function through($pipes)
    {
        $this->pipes = is_array($pipes) ? $pipes : func_get_args();

        return $this;
    }

    /**
     * Set the method to call on pipes
     *
     * @param string $method
     * @return $this
     */
    public function via($method = 'handle')
    {
        $this->method = $method;

        return $this;
    }

    /**
     * The final step to execute
     *
     * @param Closure $destination
     * @return mixed
     */
    public function then(Closure $destination)
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes()), $this->carry(), $this->prepareDestination($destination)
        );

        return $pipeline($this->passable);
    }

    /**
     * Get a Closure that represents a slice of the application onion.
     *
     * @return Closure
     */
    protected function carry()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                try {
                    if (is_callable($pipe)) {
                        // If the pipe is a callable, then we will call it directly, but otherwise we
                        // will resolve the pipes out of the dependency container and call it with
                        // the appropriate method and arguments, returning the results back out.
                        return $pipe($passable, $stack);
                    } else {
                        // If the pipe is already an object we'll just make a callable and pass it to
                        // the pipe as-is. There is no need to do any extra parsing and formatting
                        // since the object we're given was already a fully instantiated object.
                        $parameters = [$passable, $stack];
                    }

                    if (is_string($pipe)) {
                        !class_exists($pipe) ?: $pipe = new $pipe;
                    }

                    $carry = method_exists($pipe, $this->method)
                        ? $pipe->{$this->method}(...$parameters)
                        : $pipe(...$parameters);

                    return $this->handleCarry($carry);
                } catch (Throwable $e) {
                    return $this->handleException($passable, $e);
                }
            };
        };
    }


    /**
     * Get the final piece of the Closure onion.
     *
     * @param Closure $destination
     * @return Closure
     */
    protected function prepareDestination(Closure $destination)
    {
        return function ($passable) use ($destination) {
            try {
                return $destination($passable);
            } catch (Throwable $e) {
                return $this->handleException($passable, $e);
            }
        };
    }

    /**
     * Handle the value returned from each pipe before passing it to the next.
     *
     * @param  mixed  $carry
     * @return mixed
     */
    protected function handleCarry($carry)
    {
        return $carry;
    }

    /**
     * Get the array of pipes
     *
     * @return array
     */
    protected function pipes()
    {
        return $this->pipes;
    }

    /**
     * Handle an exception
     *
     * @param $passable
     * @param Throwable $e
     * @throws Throwable
     */
    protected function handleException($passable, Throwable $e)
    {
        throw $e;
    }
}