<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public function get($key)
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key) {
            return Setting::where('key', $key)->value('value');
        });
    }

    public function flushCache()
    {
        Cache::tags('settings')->flush();
    }

   
}