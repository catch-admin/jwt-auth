<?php
namespace catchAdmin\jwt\exception;

class TokenParseFailedException extends JWTException
{
    protected $message = 'token parse failed';
}