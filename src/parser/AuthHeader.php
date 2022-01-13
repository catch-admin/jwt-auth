<?php


namespace catchAdmin\jwt\parser;

use catchAdmin\jwt\contract\Parser as ParserContract;
use think\Request;

class AuthHeader implements ParserContract
{
    protected string $header = 'authorization';

    protected string $prefix = 'bearer';

    public function parse(Request $request)
    {
        $header = $request->header($this->header);

        if ($header
            && preg_match('/'.$this->prefix.'\s*(\S+)\b/i', $header, $matches)
        ) {
            return $matches[1];
        }
    }

    public function setHeaderName(string $name): static
    {
        $this->header = $name;

        return $this;
    }

    public function setHeaderPrefix(string $prefix): static
    {
        $this->prefix = $prefix;

        return $this;
    }
}
