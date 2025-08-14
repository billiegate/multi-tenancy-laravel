<?php

namespace App\Traits;

use App\Models\Landlord\Brand;
use App\Models\Landlord\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait CreateBrand
{
    /**
     * Create a new brand in the database.
     *
     * @param Tenant $tenant
     * 
     * @return Brand
     */
    public function createBrand(Tenant $tenant): Brand
    {
        // Create a new brand instance
        return Brand::create([
            'uuid' => Str::uuid()->toString(),
            'tenant_uuid' => $tenant->uuid,
        ]);
    }
}