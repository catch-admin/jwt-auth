<?php

namespace catchAdmin\jwt\claim;

/**
 */
abstract class Claim
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var mixed
     */
    protected mixed $value;

    /**
     * @param $value
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * @desc set value
     * @time 2022年01月14日
     * @param $value
     * @return $this
     */
    public function setValue($value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @desc get value
     * @time 2022年01月14日
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @desc set name
     * @time 2022年01月14日
     * @param $name
     * @return $this
     */
    public function setName($name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @desc get name
     * @time 2022年01月14日
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
