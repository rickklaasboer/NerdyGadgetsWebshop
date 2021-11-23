<?php

namespace App\Util;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request
{
    protected SymfonyRequest $original;

    public function __construct(SymfonyRequest $request)
    {
        $this->original = $request;
    }

    /**
     * Create from Symfony request
     *
     * @param SymfonyRequest $request
     * @return Request
     */
    public static function from(SymfonyRequest $request)
    {
        return new self($request);
    }

    /**
     * Merge values into existing request parameters
     *
     * @param mixed $values
     * @return $this
     */
    public function merge(array $values)
    {
        foreach ($values as $key => $value) {

            // If value is numeric, add 0 to it and replace it with the new numeric value
            if (is_numeric($value)) {
                $value = $value + 0;
            }

            $this->original->request->set($key, $value);
        }

        return $this;
    }

    /**
     * Get session from request
     *
     * @return callable|\Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public function session()
    {
        return $this->original->getSession();
    }

    /**
     * Utility function for getting only certain parameters out of request parameters
     *
     * @param array $keys
     * @return array
     */
    public function only(mixed $keys): array
    {
        if (func_get_args() > 1) {
            $keys = func_get_args();
        }

        $bag = [];

        foreach ($keys as $key) {
            $bag[$key] = $this->original->get($key);
        }

        return $bag;
    }

    /**
     * Default getter
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->original->get($name);
    }
}