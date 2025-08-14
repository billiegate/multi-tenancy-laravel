<?php

namespace App\Traits;

use App\Models\Landlord\Tenant;
use Illuminate\Support\Str;

trait CreateTenant
{
    /**
     * Create a new tenant in the database.
     *
     * @param string $tenantName
     * @param string $subdomain
     * @return Tenant
     */
    public function createTenant(
        string $tenantName,
        string $subdomain
    ): Tenant {
        // Create a new tenant instance
        return Tenant::create([
            'uuid' =>  Str::uuid()->toString(),
            'name' => $tenantName,
            'subdomain' => $subdomain
        ]);
    }
}