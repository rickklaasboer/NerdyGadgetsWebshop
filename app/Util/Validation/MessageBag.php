<?php

namespace App\Util\Validation;

class MessageBag
{
    protected array $messages = [];

    /**
     * Add to message bag
     *
     * @param string $message
     */
    public function add(string $message)
    {
        $this->messages[] = $message;
    }

    /**
     * Check if bag is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->messages) < 1;
    }

    /**
     * Get all messages
     *
     * @return array
     */
    public function all(): array
    {
        return $this->messages;
    }
}