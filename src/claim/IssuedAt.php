<?php


namespace catchAdmin\jwt\claim;

use catchAdmin\jwt\exception\TokenExpiredException;

class IssuedAt extends Claim
{
    protected string $name = 'iat';

    /**
     * @throws TokenExpiredException
     */
    public function validatePayload()
    {
        if (time() < (int)$this->getValue()) {
            throw new TokenExpiredException('Issued At (iat) timestamp cannot be in the future.');
        }
    }

    /**
     * @throws TokenExpiredException
     */
    public function validateRefresh($refreshTtl)
    {
        if (time() >= (int)$this->getValue() + $refreshTtl * 60) {
            throw new TokenExpiredException('Token has expired and can no longer be refreshed.');
        }
    }
}
