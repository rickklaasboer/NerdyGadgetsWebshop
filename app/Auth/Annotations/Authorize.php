<?php

namespace App\Auth\Annotations;

use App\Exceptions\Http\HttpForbiddenException;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Authorize
{
    private bool $allowAnonymous;

    private string $redirectTo;

    public function __construct($allowAnonymous = false, $redirectTo = null)
    {
        $this->allowAnonymous = $allowAnonymous;
        $this->redirectTo = $redirectTo;
    }

    /**
     * @throws HttpForbiddenException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function isAuthenticated()
    {
        try {
            return boolval(auth()->getUser()?->getId());
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * @return mixed|string|null
     */
    public function getRedirectTo(): mixed
    {
        return $this->redirectTo;
    }


    public function allowsAnonymous()
    {
        return $this->allowAnonymous;
    }
}