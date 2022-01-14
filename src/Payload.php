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
    protected Factory $factory;

    protected array $classMap
        = [
            'aud' => Audience::class,
            'exp' => Expiration::class,
            'iat' => IssuedAt::class,
            'iss' => Issuer::class,
            'jti' => JwtId::class,
            'nbf' => NotBefore::class,
            'sub' => Subject::class,
        ];


    /**
     * value is \Lcobucci\JWT\TokenBuilder method
     * @var array
     */
    public const CLAIMS_MAP = [
        'aud' => 'permittedFor',
        'exp' => 'expiresAt',
        'iat' => 'issuedAt',
        'iss' => 'issuedBy',
        'jti' => 'identifiedBy',
        'nbf' => 'canOnlyBeUsedAfter',
        'sub' => 'relatedTo',
    ];

    protected array $claims;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function customer(array $claim = []): static
    {
        foreach ($claim as $key => $value) {
            $this->factory->customer(
                $key,
                is_object($value) ? $value->getValue(true) : $value
            );
        }

        return $this;
    }

    public function get(): array
    {
        return $this->factory->builder()->getClaims();
    }


    public function check(bool $refresh = false): static
    {
        $this->factory->validate($refresh);

        return $this;
    }

    /**
     * @desc match class map
     *
     * @time 2022年01月14日
     * @param string $key
     * @return string|null
     */
    public function matchClassMap(string $key): ?string
    {
        return match ($key) {
            'aud' => Audience::class,
            'exp' => Expiration::class,
            'iat' => IssuedAt::class,
            'iss' => Issuer::class,
            'jti' => JwtId::class,
            'nbf' => NotBefore::class,
            'sub' => Subject::class,
            default => null,
        };
    }
}
