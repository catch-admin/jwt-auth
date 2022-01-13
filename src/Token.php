<?php

namespace catchAdmin\jwt;

class Token
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function get(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->get();
    }
}
