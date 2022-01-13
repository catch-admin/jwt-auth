<?php

use catchAdmin\jwt\command\JwtCommand;
use catchAdmin\jwt\provider\JWT as JWTProvider;
use think\Console;
use think\App;

if (!str_contains(App::VERSION, '6.0')) {
    Console::addDefaultCommands([
        JwtCommand::class
    ]);
    (new JWTProvider(app('request')))->init();
}
