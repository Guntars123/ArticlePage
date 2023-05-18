<?php declare(strict_types=1);

namespace App;

use Carbon\Carbon;

class Cache
{
    public static function remember(string $key, string $data, int $ttl = 120): void
    {
        if (is_dir('../cache')) {
            $cacheFile = '../cache/' . $key;
        } else {
            $cacheFile = 'cache/' . $key;
        }

        file_put_contents($cacheFile, json_encode([
            'expires_at' => Carbon::now()->addSeconds($ttl),
            'content' => $data
        ]));
    }

    public static function get(string $key): ?string
    {
        if (!self::has($key)) {
            return null;
        }

        if (is_dir('../cache')) {
            $cachePath = '../cache/';
        } else {
            $cachePath = 'cache/';
        }

        $content = json_decode(file_get_contents($cachePath . $key));

        return $content->content;

    }

    public static function has(string $key): bool
    {
        if (is_dir('../cache')) {
            $cachePath = '../cache/';
        } else {
            $cachePath = 'cache/';
        }

        if (!file_exists($cachePath . $key)) {
            return false;
        }
        $content = json_decode(file_get_contents($cachePath . $key));

        return Carbon::now()->lessThan(Carbon::parse($content->expires_at));
    }
}