<?php

namespace catchAdmin\jwt\contract;

interface JWTSubject
{
    /**
     * 获取用户 ID 存入到 claim
     *
     * @return mixed
     */
    public function getJWTIdentifier();

    /**
     * 返回用户自定义的 claims，并且添加到 claims
     *
     * @return array
     */
    public function getJWTCustomClaims(): array;
}