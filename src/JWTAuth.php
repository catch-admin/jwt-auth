<?php

namespace catchAdmin\jwt;

class JWTAuth extends JWT
{
    /**
     * Token 验证，返回 payload
     *
     * @param boolean $validate 是否验证黑名单
     * @return array
     * @throws exception\JWTException
     * @throws exception\TokenBlacklistException
     * @throws exception\TokenBlacklistGracePeriodException
     */
    public function auth(bool $validate = true): array
    {
        $this->manager->setValidate($validate);

        return $this->getPayload();
    }

    /**
     * Token构建
     *
     * @param  array  $user
     *
     * @return mixed
     */
    public function builder(array $user = [])
    {
        return $this->createToken($user);
    }

    /**
     * 获取Token
     *
     * @return mixed
     */
    public function token(): mixed
    {
        return $this->getToken();
    }

    /**
     * 添加Token至黑名单
     *
     * @param $token
     *
     * @return Blacklist
     */
    public function invalidate($token): Blacklist
    {
        if ($token instanceof Token) {
            return $this->manager->invalidate($token);
        }

        return $this->manager->invalidate(new Token($token));
    }

    /**
     * 是否在黑名单
     *
     * @param $token
     *
     * @return bool
     */
    public function validate($token): bool
    {
        if ($token instanceof Token) {
            return $this->manager->validate($this->manager->getProvider()->decode($token->get()));
        }

        return $this->manager->validate($this->manager->getProvider()->decode($token));
    }
}
