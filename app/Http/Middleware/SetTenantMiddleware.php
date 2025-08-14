<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Landlord\Tenant;

/**
 * SetTenantMiddleware is responsible for identifying the tenant based on the request
 * and setting the appropriate database connection for multi-tenancy.
 */
class SetTenantMiddleware
{
    use \App\Traits\Connection;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get tenant identifier from request
        $host = $request->getHost();
        
        $explodedHost = explode('.', $host);

        $tenantId = $request->route('tenant') ?? $request->header('X-Tenant-Id');
        if (!$tenantId && count($explodedHost) > 2 && $host !== '127.0.0.1') {
            // Assuming the tenant identifier is the first part of the subdomain
            $tenantId = implode('.', array_slice($explodedHost, 0, -2));
        }

        if (!$tenantId) {
            // Handle missing tenant identifier
            return $this->terminateRequest($request, 403);
        }

        // 2. Find tenant in landlord database
        $tenant = Tenant::with(['connection'])
                    ->where('uuid', $tenantId)
                    ->orWhere('subdomain', $tenantId)
                    ->first();

        // Check if tenant exists
        if (!$tenant) {
            return $this->terminateRequest($request, 404);
        }

        $request->merge(['tenant' => json_encode($tenant)]);

        // 3. Configure and set the tenant's database connection
        $this->connect($tenant);
       
        return $next($request);
    }

    /**
     * TerminateRequest the middleware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return response
     * @param  int  $status
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function terminateRequest(Request $request, int $status = 404): response  
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
