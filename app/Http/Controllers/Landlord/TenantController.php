<?php
namespace App\Http\Controllers\Landlord;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Landlord\Tenant;
use App\Models\Landlord\Brand;
use Illuminate\Support\Facades\Artisan;

/**
 * TenantController handles the management of tenants in a multi-tenant application.
 * It provides methods to create, read, update, and delete tenant records.
 */ 
class TenantController extends Controller
{

    use \App\Traits\Connection;
    use \App\Traits\CreateTenant;
    use \App\Traits\CreateBrand;

    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with('brand', 'connection')
            ->orderBy('created_at', 'desc')
            ->get();
    
        return response()->json($tenants, 200);
    }

    public function show()
    {
        $tenant = json_decode(request()->get('tenant'), true) ?? [];
        $tenant_brand = Brand::where('tenant_uuid', $tenant['uuid'])
            ->first();
        $tenant['brand'] = $tenant_brand ? $tenant_brand->toArray() : null;

        return response()->json($tenant, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|max:255|unique:tenants,subdomain',
        ]);

        $databaseName = 'tenant_' . strtolower(str_replace(' ', '_', $data['subdomain']));
        $database = $this->createTenantDatabase($databaseName);

        $tenant = $this->createTenant($data['name'], $data['subdomain']);
        $this->createTenantConnection($tenant, $database);
        $this->createBrand($tenant);

        $this->reconnect($tenant);

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true // Use --force in production to run migrations without a prompt
        ]);

        return response()->json($tenant, 201);
    }


}