<?php

namespace catchAdmin\jwt;

use catchAdmin\jwt\command\JwtCommand;

class JwtService extends \think\Service
{
    public function boot()
    {
        $this->commands(JwtCommand::class);
    }
}
