<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $connection = 'tenant';
    protected $guarded = [];
}
