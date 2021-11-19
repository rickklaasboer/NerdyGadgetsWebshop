<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\Request;

class Url
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Match request url against given path
     *
     * @param string $path
     * @return bool
     */
    public function is(string $path): bool
    {
        return $path === $this->request->getPathInfo();
    }

    /**
     * Get the previous url
     *
     * @param string $fallback
     * @return string
     */
    public function prev(string $fallback = '/'): string
    {
        return $this->request->getSession()
            ->get('prev', $fallback);
    }
}