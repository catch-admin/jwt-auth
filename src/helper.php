<?php

use catchAdmin\jwt\command\SecretCommand;
use catchAdmin\jwt\provider\JWT as JWTProvider;
use think\Console;
use think\App;

if (strpos(App::VERSION, '6.0') === false) {
    Console::addDefaultCommands([
        SecretCommand::class
    ]);
    (new JWTProvider(app('request')))->init();
}
