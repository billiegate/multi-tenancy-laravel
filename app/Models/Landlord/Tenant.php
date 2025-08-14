<?php
namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $connection = 'landlord';
    protected $guarded = [];

    /**
     * Get the connection associated with the tenant.
     */
    public function connection()
    {
        return $this->hasOne(Connection::class, 'tenant_uuid', 'uuid');
    }

    /**
     * Get the brand associated with the tenant.
     */
    public function brand()
    {
        return $this->hasOne(Brand::class, 'tenant_uuid', 'uuid');
    }
}