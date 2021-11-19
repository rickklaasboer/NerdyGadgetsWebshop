<?php

namespace App\Auth;

use App\Entities\User;

class Auth
{
    protected ?User $user = null;

    /**
     * Check if a user is logged in
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return (bool)$this->user;
    }

    /**
     * Get currently logged-in user
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set currently logged-in user
     *
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}