<?php

namespace catchAdmin\jwt\provider;

use catchAdmin\jwt\Blacklist;
use catchAdmin\jwt\claim\Factory;
use catchAdmin\jwt\facade\JWTAuth;
use catchAdmin\jwt\Manager;
use catchAdmin\jwt\parser\AuthHeader;
use catchAdmin\jwt\parser\Cookie;
use catchAdmin\jwt\parser\Param;
use catchAdmin\jwt\Payload;
use catchAdmin\jwt\provider\JWT\Lcobucci;
use think\Request;
use think\facade\App;

class JWT
{
    private $request;

    private array $config;

    public function register(): void
    {
        $this->config = config('jwt');

        $this->request = App::make(Request::class);

        if (! empty($this->config)) {
            $this->registerBlacklist();

            $this->registerProvider();

            $this->registerFactory();

            $this->registerPayload();

            $this->registerManager();

            $this->registerJWTAuth();
        }
    }

    /**
     * register blacklist
     *
     * @time 2022年01月17日
     */
    protected function registerBlacklist()
    {
        App::make(Blacklist::class, [
            new Storage()
        ])->setRefreshTTL($this->config['refresh_ttl'])
        ->setGracePeriod($this->config['blacklist_grace_period']);
    }

    /**
     * register provider
     *
     * @time 2022年01月17日
     */
    protected function registerProvider()
    {
        //builder asymmetric keys
        $keys = $this->config['secret'] ??
            [
                'public' => $this->config['public_key'],
                'private' => $this->config['private_key'],
                'password' => $this->config['password'],
            ];

        App::make(Lcobucci::class, [
            $this->config['algo'],
            $keys,
        ]);
    }

    /**
     * register factory
     *
     * @time 2022年01月17日
     */
    protected function registerFactory()
    {
        App::make(Factory::class, [
            new Request(),
            $this->config['ttl'],
            $this->config['refresh_ttl'],
        ]);
    }

    /**
     * register payload
     *
     * @time 2022年01月17日
     */
    protected function registerPayload()
    {
        App::make(Payload::class, [
            App::make(Factory::class)
        ]);
    }

    /**
     * register manager
     *
     * @time 2022年01月17日
     */
    protected function registerManager()
    {
        App::make(Manager::class, [
            App::make(Blacklist::class),

            App::make(Payload::class),

            App::make(Lcobucci::class)
        ]);
    }

    /**
     * register jwt auth
     *
     * @time 2022年01月17日
     */
    protected function registerJWTAuth()
    {
        $chains = [
            'header' => new AuthHeader(),
            'cookie' => new Cookie(),
            'param'  => new Param()
        ];

        $mode = $this->config['token_mode'];

        $setChain = [];

        foreach ($mode as $key => $chain) {
            if (isset($chains[$chain])) {
                $setChain[$key] = $chains[$chain];
            }
        }

        JWTAuth::parser()->setRequest($this->request)->setChain($setChain);
    }
}
