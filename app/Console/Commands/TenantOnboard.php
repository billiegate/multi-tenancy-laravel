<?php
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor; // Your central Vendor model

class TenantOnboard extends Command
{
    protected $signature = 'tenant:onboard {tenant?:The name of the tenant} {--u|username=} {--p|password=} {--h|host=}';
    // ... (description, etc.)

    public function handle()
    {
        $tenantName = $this->argument('tenant');

        if (!$tenantName) {
            $this->fail('This command requirement a tenant name.');
        }

        $vendor = Vendor::find($tenantName);

        if (!$vendor) {
            $this->error("Vendor not found!");
            return;
        }

        $username = $this->option('username');
        if (!$username) {
            $username = $this->ask('What is the database username?', 'root');
        }

        $password = $this->option('password');
        if (!$password) {
            $password = $this->secret('What is the database password?', 'password');
        }

        // 1. Create the new database
        $databaseName = 'tenant_' . $tenantName;
        DB::statement("CREATE DATABASE `{$databaseName}`");
        $this->info("Database '{$databaseName}' created successfully.");

        // 2. Dynamically set the connection to the new database
        config(['database.connections.tenant.database' => $databaseName]);
        DB::reconnect('tenant');

        // 3. Run migrations on the new database
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant' // Store tenant migrations separately
        ]);
        $this->info("Migrations ran successfully for vendor '{$tenantName}'.");
    }
}