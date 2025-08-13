<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantConnectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get tenant identifier from request
        $host = $request->getHost();
        $explodedHost = explode('.', str_replace(['http://', 'https://'], '', $host));

        $tenantId = $request->route('tenant') ?? $request->header('X-Tenant-Id');
        if (!$tenantId && count($explodedHost) > 2) {
            // Assuming the tenant identifier is the first part of the subdomain
            $tenantId = implode('.', array_slice($explodedHost, 0, -2));
        }

        if (!$tenantId) {
            // Handle missing tenant identifier
            return $this->terminate($request, 403);
        }

        // 2. Find tenant in landlord database
        $tenant = DB::connection('landlord')
                    ->table('tenants')
                    ->where('uuid', $tenantId)
                    ->first();

        // Check if tenant exists
        if (!$tenant) {
            return $this->terminate($request, 404);
        }

        // 3. Configure and set the tenant's database connection
        config(['database.connections.tenant.database' => $tenant->db_name]);
        config(['database.connections.tenant.host' => $tenant->db_host]);
        // ... set other details

        DB::setDefaultConnection('tenant');
       
        return $next($request);
    }

    /**
     * Terminate the middleware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function terminate(Request $request, int $status = 404): void    
    {
        if ($request->wantsJson()) {
            return response()->json([
                "message"   =>  'No tenant identifier provided.',
                "data"      =>  null,
                "code"      =>  '99',
            ], $status);
        }
        abort($status, 'Tenant identifier is required.');
    }
}
