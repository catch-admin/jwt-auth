<?php


namespace catchAdmin\jwt\contract;

interface Storage
{
    public function set(string $key, string $val, $time = 0);

    public function get(string $key);

    public function delete(string $key);
}
