<?php


namespace catchAdmin\jwt;

use catchAdmin\jwt\command\SecretCommand;
use catchAdmin\jwt\middleware\InjectJwt;
use catchAdmin\jwt\provider\JWT as JWTProvider;

class Service extends \think\Service
{
    public function boot()
    {
        $this->commands(SecretCommand::class);
        $this->app->middleware->add(InjectJwt::class);
    }
}
