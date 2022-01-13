<?php

namespace catchAdmin\jwt\exception;

class TokenMissingException extends JWTException
{
    protected $message = 'token missing';
}
