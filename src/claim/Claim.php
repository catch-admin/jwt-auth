<?php

namespace catchAdmin\jwt\claim;

abstract class Claim
{
    protected string $name;

    private string $value;

    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setName($name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
