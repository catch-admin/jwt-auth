<?php


namespace catchAdmin\jwt;

class Token
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function get()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->get();
    }
}
