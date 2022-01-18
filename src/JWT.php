<?php

namespace catchAdmin\jwt;

use catchAdmin\jwt\exception\BadMethodCallException;
use catchAdmin\jwt\parser\Parser;
use catchAdmin\jwt\exception\JWTException;

/**
 * JWT
 */
class JWT
{
    /**
     * @var Manager
     */
    protected Manager $manager;

    /**
     * @var Parser
     */
    protected Parser $parser;

    /**
     * @var mixed
     */
    protected $token = null;

    /**
     * @param Manager $manager
     * @param Parser $parser
     */
    public function __construct(Manager $manager, Parser $parser)
    {
        $this->manager = $manager;

        $this->parser  = $parser;
    }

    /**
     * @desc parser
     * @time 2022年01月14日
     * @return Parser
     */
    public function parser(): Parser
    {
        return $this->parser;
    }

    /**
     * @desc create token
     * @time 2022年01月14日
     * @param array $customerClaim
     * @return string
     */
    public function createToken(array $customerClaim = []): string
    {
        return $this->manager->encode($customerClaim)->get();
    }

    /**
     * @time 2022年01月13日
     * @return $this
     * @throws JWTException
     * @throws exception\TokenParseFailedException
     */
    public function parseToken(): static
    {
        if (! $token = $this->parser->parseToken()) {
            throw new JWTException('No token is this request.');
        }

        $this->setToken($token);

        return $this;
    }

    /**
     * @desc get token
     *
     * @time 2022年01月14日
     * @return string|null
     */
    public function getToken(): ?string
    {
        if ($this->token === null) {
            try {
                $this->parseToken();
            } catch (JWTException $e) {
                $this->token = null;
            }
        }

        return $this->token;
    }

    /**
     * @desc set token
     *
     * @time 2022年01月14日
     * @param $token
     * @return $this
     */
    public function setToken($token): static
    {
        $this->token = $token instanceof Token ? $token : new Token($token);

        return $this;
    }

    /**
     * @throws JWTException
     */
    public function requireToken()
    {
        $this->getToken();

        if (! $this->token) {
            throw new JWTException('Must have token');
        }
    }

    /**
     * 获取Payload
     * @return mixed
     * @throws JWTException
     * @throws exception\TokenBlacklistException
     */
    public function getPayload(): mixed
    {
        $this->requireToken();

        return $this->manager->decode($this->token);
    }

    /**
     * 刷新Token
     *
     * @throws JWTException
     */
    public function refresh(): string
    {
        $this->parseToken();

        return $this->manager->refresh($this->token)->get();
    }


    /**
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->manager, $method)) {
            return call_user_func_array([$this->manager, $method], $parameters);
        }

        throw new BadMethodCallException("Method [$method] does not exist.");
    }
}
