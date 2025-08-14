<?php
namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $connection = 'landlord';
    protected $guarded = [];

    /**
     * Get the tenant associated with the brand.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_uuid', 'uuid');
    }
}