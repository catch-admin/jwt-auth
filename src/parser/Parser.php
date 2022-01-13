<?php


namespace catchAdmin\jwt\parser;

use catchAdmin\jwt\exception\TokenParseFailedException;
use think\Request;

class Parser
{
    protected Request $request;

    private array $chain;

    public function __construct(Request $request, $chain = [])
    {
        $this->request = $request;

        $this->chain   = $chain;
    }

    public function setRequest(Request $request): static
    {
        $this->request = $request;

        return $this;
    }

    public function setChain(array $chain): static
    {
        $this->chain = $chain;

        return $this;
    }

    public function getChain()
    {
        return $this->chain;
    }

    /**
     * @throws TokenParseFailedException
     */
    public function parseToken()
    {
        foreach ($this->chain as $parser) {
            if ($response = $parser->parse($this->request)) {
                return $response;
            }
        }

        throw new TokenParseFailedException();
    }

    /**
     * @time 2022年01月13日
     * @return bool
     * @throws TokenParseFailedException
     */
    public function hasToken(): bool
    {
        return $this->parseToken() !== null;
    }
}
