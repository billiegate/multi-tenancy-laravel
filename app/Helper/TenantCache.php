<?php
namespace App\Helper;

use Illuminate\Support\Facades\Cache;

class TenantCache extends Cache{

    public static function get(string $key)
    {
        $tenant = json_decode(request()->get('tenant'), true) ?? [];
        $tenantId = $tenant['id'] ?? null;
        return static::get("{$tenantId}:{$key}");
    }

    public static function put(string $key, $value, $ttl = null)
    {
        $tenant = json_decode(request()->get('tenant'), true) ?? [];
        $tenantId = $tenant['id'] ?? null;
        return static::put("{$tenantId}:{$key}", $value, $ttl);
    }
}