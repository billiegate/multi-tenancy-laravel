<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Landlord\Tenant;


class TenantOnboard extends Command
{
    use \App\Traits\CreateTenant;
    use \App\Traits\CreateBrand;
    use \App\Traits\Connection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:onboard {tenant?} {--d|subdomain=}  {--u|username=} {--p|password=} {--H|host=} {--driver=sqlite}';
    
    protected $description = 'Onboard a new tenant by creating a database and running migrations.';
   
    function clean($string) {
        $string = str_replace(' ', '-', $string);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }

    public function handle()
    {
        $tenantName = $this->argument('tenant');

        if (!$tenantName) {
            $this->fail('Please provide a tenant name to create eg. php artisan tenant:onboard "TenantName"');
        }

        $subdomain = $this->option('subdomain');
        if (!$subdomain) {
            $subdomain = $this->clean($tenantName);
            $subdomain = strtolower($subdomain);
            $subdomain = $this->ask('Can we use this as the tenant subdomain?', $subdomain);
        }

        if (!$subdomain) {
            $this->fail('The tenant subdomain is required.');
        }

        $tenant = DB::connection('landlord')
                    ->table('tenants')
                    ->where('name', $tenantName)
                    ->orWhere('subdomain', $subdomain)
                    ->first();

        if ($tenant) {
            $this->fail('A tenant with this name or subdomain already exists.');
        }

        $username = $this->option('username'); // if there is a different host, we can use a different username
        if (!$username) {
            $username = $this->ask('What is the database username?', 'root') ?? 'root';
        }

        $password = $this->option('password'); // if there is a different host, we can use a different password
        if (!$password) {
            $password = $this->secret('What is the database password?', 'password') ?? 'password';
        }

        $driver = $this->option('driver'); 

        // 1. Create the new database
        $this->info("Creating database for tenant '{$tenantName}' with subdomain '{$subdomain}'...");
        $databaseName = 'tenant_' . $subdomain;

        if ($driver === 'sqlite') {
            $databaseName = database_path("{$databaseName}.sqlite");
            if (file_exists($databaseName)) {
                $this->fail("The SQLite database file '{$databaseName}' already exists.");
            }
        } else {
            DB::statement("CREATE DATABASE `{$databaseName}`");
        }
        $this->info("Database '{$databaseName}' created successfully.");

        // 2. Create the tenant in the landlord database
        $this->info("Creating tenant '{$tenantName}' with subdomain '{$subdomain}'...");
        $tenant = $this->createTenant($tenantName, $subdomain);
        $this->info("Tenant '{$tenantName}' with subdomain '{$subdomain}' created successfully.");

        // Store the tenant connection details in the landlord database
        $this->info("Storing connection details for tenant '{$tenantName}'...");
        $this->createTenantConnection($tenant, $databaseName, $username, $password, $this->option('host') ?? 'localhost');
        $this->info("Connection details for tenant '{$tenantName}' stored successfully.");

        // Create a brand for the tenant
        $this->info("Creating tenant default brand for '{$tenantName}'...");
        $this->createBrand($tenant);
        $this->info("Default brand created successfully for tenant '{$tenantName}'.");

        // 2. Dynamically set the connection to the new database (if we need to scalfold the tenant migrations)

        
        $this->info("Running migrations for tenant '{$tenantName}'...");
        $this->reconnect($tenant);

        // 3. Run migrations on the new database
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true // Use --force in production to run migrations without a prompt
        ]);

        $this->info("Migrations ran successfully for vendor '{$tenantName}'.");
    }
}