<?php


namespace catchAdmin\jwt\parser;

trait KeyTrait
{
    private $key = 'token';

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
