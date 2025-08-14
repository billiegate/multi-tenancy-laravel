<?php
namespace App\Traits;

use App\Models\Landlord\Tenant;
use App\Models\Landlord\Connection as TenantConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait Connection
{
    /**
     * Create a new tenant database connection.
     *
     * @param string $databaseName
     * @param string $driver
     * @return void
     */
    public function createTenantDatabase(string $databaseName, string $driver = 'sqlite'): string
    {
        if ($driver === 'sqlite') {
            // For SQLite, we create a file-based database
            $database = database_path("{$databaseName}.sqlite");
        } else {
            // For MySQL or other databases, we need to create a new database
            DB::connection('landlord')->statement(
                "CREATE DATABASE IF NOT EXISTS `{$databaseName}`"
            );
            $database = $databaseName;
        }

        return $database;
    }

    /**
     * Create a new tenant connection.
     *
     * @param Tenant $tenant
     * @param string $databaseName
     * @param string $username
     * @param string $password
     * @param string $host
     * 
     * @return TenantConnection
     */
    public function createTenantConnection(Tenant $tenant, string $databaseName, string $username = 'root', string $password = 'password', string $host = 'localhost'): TenantConnection
    {
        return TenantConnection::create([
            'uuid' => Str::uuid()->toString(),
            'tenant_uuid' => $tenant->uuid,
            'db_name' => $databaseName,
            'db_host' => $host,
            'db_username' => $username,
            'db_password' => $password,
            'db_port' => 3306, // Default MySQL port
            'db_driver' => 'mysql',
            'db_charset' => 'utf8mb4',
            'db_collation' => 'utf8mb4_unicode_ci',
            'db_prefix' => '',
            'db_engine' => 'InnoDB',
        ]);
    }

    /**
     * Set the tenant connection for the current request.
     *
     * @param Tenant $tenant
     * @return void
     */
    public function connect(Tenant $tenant): void
    {
        // Set the tenant connection configuration
        config(['database.connections.tenant.database' => $tenant->connection->db_name]);
        config(['database.connections.tenant.host' => $tenant->connection->db_host]);

        // config(['database.connections.tenant.username' => $tenant->connection->db_username]);
        // config(['database.connections.tenant.password' => $tenant->connection->db_password]);
        // config(['database.connections.tenant.port' => $tenant->connection->db_port]);

        // Set the default connection to tenant
        DB::setDefaultConnection('tenant');
    }

   /**
     * Update the tenant connection to the current request.
     *
     * @param Tenant $tenant
     * @return void
     */
    public function reconnect(Tenant $tenant): void
    {
        // Update the tenant connection configuration
        
        config(['database.connections.tenant.database' => $tenant->connection->db_name]);
        DB::reconnect('tenant');

        DB::purge('tenant');
    }

}