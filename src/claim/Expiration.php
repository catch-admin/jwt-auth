<?php


namespace catchAdmin\jwt\claim;

use catchAdmin\jwt\exception\TokenExpiredException;

class Expiration extends Claim
{
    protected $name = 'exp';

    public function validatePayload()
    {
        if (time() >= (int)$this->getValue()) {
            throw new TokenExpiredException('The token is expired.');
        }
    }
}
