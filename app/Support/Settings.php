<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Settings
{
    public static function all(): array
    {
        return Cache::rememberForever('gs.settings', function () {
            if (! Schema::hasTable('settings')) {
                return [];
            }

            return Setting::pluck('value', 'key')->all();
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::all()[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('gs.settings');
    }

    public static function flush(): void
    {
        Cache::forget('gs.settings');
    }
}
