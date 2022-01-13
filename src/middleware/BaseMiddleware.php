<?php


namespace catchAdmin\jwt\middleware;

use catchAdmin\jwt\JWTAuth as Auth;
use think\facade\Cookie;
use think\facade\Config;

class BaseMiddleware
{
    protected Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    protected function setAuthentication($response, $token = null)
    {
        $token = $token ?: $this->auth->refresh();
        $this->auth->setToken($token);

        if (in_array('cookie', Config::get('jwt.token_mode'))) {
            Cookie::set('token', $token);
        }
        
        if (in_array('header', Config::get('jwt.token_mode'))) {
            $response = $response->header(['Authorization' => 'Bearer '.$token]);
        }

        return $response;
    }
}
