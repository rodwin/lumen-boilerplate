<?php

namespace App\Repositories;

abstract class BaseCacheRepository
{
    /**
     * This variable has to be set in order to tag keys and values in the cache.
     *
     * @var string
     */
    public static $tag;

    /**
     * Generate a cache key from an array usually coming from Illuminate\Http\Request::all().
     */
    public static function keyFromData(array $data) : string
    {
        return md5(serialize($data));
    }

    /**
     * Wrapper around Illuminate\Cache\Repository::remember() to keep the code DRY.
     *
     * @return mixed
     */
    protected function remember(string $key, \Closure $callback, int $time = 60)
    {
        return app('cache')->tags(self::$tag)->remember($key, $time, $callback);
    }
}
