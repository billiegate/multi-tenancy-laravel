<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\Landlord\Tenant;

class TenantMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate {tenant?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all tenant databases or a specific tenant database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantid = $this->argument('tenant');

        if ($tenantid) {
            $this->info("Migrating database for tenant: {$tenantid}...");

            $tenant = Tenant::with('connection')->where('id', $tenantid)->first();
            if (!$tenant) {
                $this->fail("Tenant with ID {$tenantid} not found.");
            }
            // Dynamically set the connection for the current tenant
            config(['database.connections.tenant.database' => $tenant->connection->db_name]);
            DB::reconnect('tenant');
            // Run migrations on the current tenant's database
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true // Use --force in production to run migrations without a prompt
            ]);
            $this->info("Migrations complete for tenant: {$tenant->name}.");
        } else {
            $this->info("Migrating all tenant databases...");

            // Get all tenants from the central database
            $tenants = Tenant::with('connection')->get();

            foreach ($tenants as $tenant) {
                // Dynamically set the connection for the current tenant
                config(['database.connections.tenant.database' => $tenant->connection->db_name]);
                DB::reconnect('tenant');

                $this->info("Migrating database for vendor: {$tenant->name}...");

                // Run migrations on the current tenant's database
                Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path' => 'database/migrations/tenant',
                    '--force' => true // Use --force in production to run migrations without a prompt
                ]);

                $this->info("Migrations complete for vendor: {$tenant->name}.");
            }
        }

        $this->info("All tenant databases have been migrated successfully.");

    }
}
