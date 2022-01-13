<?php

namespace catchAdmin\jwt\provider;

use think\facade\Cache;

class Storage
{
    public function delete($key): bool
    {
        return Cache::delete($key);
    }

    public function get($key)
    {
        return Cache::get($key);
    }

    public function set($key, $val, int $time = 0): bool
    {
        return Cache::set($key, $val, $time);
    }
}