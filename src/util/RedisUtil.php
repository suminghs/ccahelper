<?php

namespace Ccahouse\Ccahelper;

use \Illuminate\Support\Facades\Redis;

/**
 * Class Redis
 * @package App\Services
 * Redis封装类
 */
class RedisUtil
{
    private static $prefix = '';

    public function __construct()
    {
        self::$prefix = env('REDIS_PREFIX').':';
    }

    public static function setObject($key, $val, $expire = false)
    {
        $val = serialize($val);
        if ($expire) {
            Redis::setex(self::$prefix . $key, $expire, $val);
        } else {
            Redis::set(self::$prefix . $key, $val);
        }
    }

    public static function getObject($key)
    {
        $val = Redis::get(self::$prefix . $key);
        return $val ? unserialize($val) : null;
    }

    public static function set($key, $val, $expire = false)
    {
        if ($expire) {
            Redis::setex(self::$prefix . $key, $expire, $val);
        } else {
            Redis::set(self::$prefix . $key, $val);
        }
    }

    public static function get($key)
    {
        return Redis::get(self::$prefix . $key);
    }

    public static function exists($key)
    {
        return Redis::exists(self::$prefix . $key);
    }

    public static function del($key)
    {
        Redis::del(self::$prefix . $key);
    }

    public static function incr($key)
    {
        Redis::incr(self::$prefix . $key);
    }

    public static function decr($key)
    {
        Redis::decr(self::$prefix . $key);
    }

}