<?php


namespace catchAdmin\jwt\claim;

use think\Request;

class Factory
{
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

    protected int $ttl;

    protected array $claim = [];

    protected int $refreshTtl;

    private Request $request;

    public function __construct(Request $request, int $ttl, int $refreshTtl)
    {
        $this->request    = $request;
        $this->ttl        = $ttl;
        $this->refreshTtl = $refreshTtl;
    }

    public function customer(string $key, string $value): static
    {
        $this->claim[$key] = isset($this->classMap[$key])
            ? new $this->classMap[$key]($value)
            : new Customer($key, $value);

        return $this;
    }

    public function builder(): static
    {
        $claim = [];

        foreach ($this->classMap as $key => $class) {
            $claim[$key] = new $class(method_exists($this, $key)
                ? $this->$key() : '');
        }

        $this->claim = array_merge($this->claim, $claim);

        return $this;
    }

    public function validate($refresh = false)
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

    public function getClaims(): array
    {
        return $this->claim;
    }

    public function aud(): string
    {
        return $this->request->url();
    }

    public function exp(): int
    {
        return time() + $this->ttl;
    }

    public function iat(): int
    {
        return time();
    }

    public function iss(): string
    {
        return $this->request->url();
    }

    public function jti(): string
    {
        return md5(uniqid().time().rand(100000, 9999999));
    }

    public function nbf(): int
    {
        return time();
    }
}
