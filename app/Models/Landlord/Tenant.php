<?php
namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $connection = 'landlord';
    protected $guarded = [];
}