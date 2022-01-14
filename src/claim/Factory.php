<?php

namespace catchAdmin\jwt\claim;

use think\Request;

/**
 * claim factory
 */
class Factory
{
    /**
     * @var array|string[]
     */
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
     * @var int
     */
    protected int $ttl;

    /**
     * @var array
     */
    protected array $claim = [];

    /**
     * @var int
     */
    protected int $refreshTtl;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @param Request $request
     * @param int $ttl
     * @param int $refreshTtl
     */
    public function __construct(Request $request, int $ttl, int $refreshTtl)
    {
        $this->request    = $request;

        $this->ttl        = $ttl;

        $this->refreshTtl = $refreshTtl;
    }

    /**
     * @desc customer
     * @time 2022年01月14日
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function customer(string $key, mixed $value): static
    {
        if ($class = $this->matchClaimClass($key)) {
            $this->claim[$key] = new $class($value);
        } else {
            $this->claim[$key] = new Customer($key, $value);
        }

        return $this;
    }

    /**
     * @desc builder
     * @time 2022年01月14日
     * @return $this
     */
    public function builder(): static
    {
        $claims = [];

        foreach ($this->classMap as $key => $class) {
            $claims[$key] = new $class(method_exists($this, $key) ? $this->$key() : '');
        }

        $this->claim = array_merge($this->claim, $claims);

        return $this;
    }

    /**
     * @desc validate
     * @time 2022年01月14日
     * @param false $refresh
     */
    public function validate(bool $refresh = false)
    {
        foreach ($this->claim as $claim) {
            if (! $refresh && method_exists($claim, 'validatePayload')) {
                $claim->validatePayload();
            }
            if ($refresh && method_exists($claim, 'validateRefresh')) {
                $claim->validateRefresh($this->refreshTtl);
            }
        }
    }

    /**
     * @desc get claims
     * @time 2022年01月14日
     * @return array
     */
    public function getClaims(): array
    {
        return $this->claim;
    }

    /**
     * @desc aud
     * @time 2022年01月14日
     * @return string
     */
    public function aud(): string
    {
        return $this->request->url();
    }

    /**
     * @desc expired at
     * @time 2022年01月14日
     * @return \DateTimeImmutable
     */
    public function exp(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp(time() + $this->ttl);
    }

    /**
     * @desc iat
     * @time 2022年01月14日
     * @return \DateTimeImmutable
     */
    public function iat(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp(time());
    }

    /**
     * @desc iss
     * @time 2022年01月14日
     * @return string
     */
    public function iss(): string
    {
        return $this->request->url();
    }

    /**
     * @desc md5
     * @time 2022年01月14日
     * @return string
     */
    public function jti(): string
    {
        return md5(uniqid().time().rand(100000, 9999999));
    }

    /**
     * @desc nbf
     * @time 2022年01月14日
     * @return \DateTimeImmutable
     */
    public function nbf(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp(time());
    }

    /**
     * @desc match claims
     *
     * @time 2022年01月14日
     * @param $claim
     * @return string|null
     */
    protected function matchClaimClass($claim): ?string
    {
        return match ($claim) {
            'aud' => Audience::class,
            'exp' => Expiration::class,
            'iat' => IssuedAt::class,
            'iss' => Issuer::class,
            'jti' => JwtId::class,
            'nbf' => NotBefore::class,
            'sub' => Subject::class,
            default => null
        };
    }
}
