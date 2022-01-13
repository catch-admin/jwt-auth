<?php


namespace catchAdmin\jwt;

use catchAdmin\jwt\claim\Factory;
use catchAdmin\jwt\claim\Issuer;
use catchAdmin\jwt\claim\Audience;
use catchAdmin\jwt\claim\Expiration;
use catchAdmin\jwt\claim\IssuedAt;
use catchAdmin\jwt\claim\JwtId;
use catchAdmin\jwt\claim\NotBefore;
use catchAdmin\jwt\claim\Subject;

class Payload
{
    protected $factory;

    protected $classMap
        = [
            'aud' => Audience::class,
            'exp' => Expiration::class,
            'iat' => IssuedAt::class,
            'iss' => Issuer::class,
            'jti' => JwtId::class,
            'nbf' => NotBefore::class,
            'sub' => Subject::class,
        ];

    protected $claims;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function customer(array $claim = [])
    {
        foreach ($claim as $key => $value) {
            $this->factory->customer(
                $key,
                is_object($value) ? $value->getValue() : $value
            );
        }

        return $this;
    }

    public function get()
    {
        $claim = $this->factory->builder()->getClaims();

        return $claim;
    }

    public function check($refresh = false)
    {
        $this->factory->validate($refresh);

        return $this;
    }
}
