<?php

namespace catchAdmin\jwt;

use catchAdmin\jwt\provider\Storage;

class Blacklist
{
    protected Storage $storage;
    protected int $refreshTTL = 20160;
    protected int $gracePeriod = 0;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function add($payload): static
    {
        $this->set($this->getKey($payload), $this->getGraceTimestamp(), $this->getSecondsUntilExpired($payload));

        return $this;
    }

    public function has($payload): bool
    {
        return (bool) $this->get($this->getKey($payload));
    }

    public function hasGracePeriod($payload): bool
    {
        $val = (int) $this->get($this->getKey($payload));

        return  $val && $val >= time();
    }

    protected function getKey(array $payload)
    {
        return $payload['jti']->getValue();
    }

    public function set($key, $val, $time = 0): static
    {
        $this->storage->set($key, $val, $time);

        return $this;
    }

    public function get($key)
    {
        return $this->storage->get($key);
    }

    public function remove($key): bool
    {
        return $this->storage->delete($key);
    }

    public function getRefreshTTL(): int
    {
        return $this->refreshTTL;
    }

    public function setRefreshTTL(int $ttl): static
    {
        $this->refreshTTL = $ttl;

        return $this;
    }

    public function getGracePeriod(): int
    {
        return $this->gracePeriod;
    }

    public function setGracePeriod(int $gracePeriod): static
    {
        $this->gracePeriod = $gracePeriod;

        return $this;
    }

    protected function getSecondsUntilExpired(array $payload)
    {
        $iat = $payload['iat']->getValue();

        return $iat + $this->getRefreshTTL() * 60 - time();
    }

    protected function getGraceTimestamp(): int
    {
        return time() + $this->gracePeriod;
    }
}
