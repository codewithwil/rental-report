<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheableResource
{
    protected static function bootCacheableResource()
    {
        static::saved(function () {
            static::forgetCache();
        });

        static::deleted(function () {
            static::forgetCache();
        });
    }

    public static function getCacheKey(): string
    {
        return static::$cacheKey;  
    }

    public static function forgetCache()
    {
        Cache::forget(static::getCacheKey());
    }

    public static function getCachedActive($ttl = 86400) 
    {
        return Cache::remember(static::getCacheKey(), $ttl, function () {
            return static::select(static::$cacheColumns)
                ->where('status', static::STATUS_ACTIVE)
                ->orderBy('created_at', 'asc')
                ->get();
        });
    }
}
