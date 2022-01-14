<?php

namespace catchAdmin\jwt;

use catchAdmin\jwt\command\JwtCommand;
use catchAdmin\jwt\provider\JWT as JWTProvider;

class JwtService extends \think\Service
{
    public function boot()
    {
        $this->commands(JwtCommand::class);

        (new JWTProvider())->register();
    }
}
