<?php


namespace catchAdmin\jwt\claim;

class Customer extends Claim
{
    public function __construct(string $name, string $value)
    {
        parent::__construct($value);

        $this->setName($name);
    }
}
