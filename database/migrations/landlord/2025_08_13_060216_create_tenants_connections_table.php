<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(DB::raw('(UUID())'))
                ->comment('string representation to uniquely identify this entity');
            $table->uuid('tenant_uuid')
                ->comment('string representation to uniquely identify this entity');
            $table->string('db_name')
                ->unique()
                ->comment('the name of the database for this tenant');
            $table->string('db_host')
                ->default('localhost')
                ->comment('the host of the database for this tenant');
            $table->string('db_username')
                ->default('root')
                ->comment('the username for the database connection');
            $table->string('db_password')
                ->default('password')
                ->comment('the password for the database connection');
            $table->string('db_port')
                ->default('3306')
                ->comment('the port for the database connection');
            $table->string('db_driver')
                ->default('mysql')
                ->comment('the driver for the database connection');
            $table->string('db_charset')
                ->default('utf8mb4')
                ->comment('the charset for the database connection');
            $table->string('db_collation')
                ->default('utf8mb4_unicode_ci')
                ->comment('the collation for the database connection');
            $table->string('db_prefix')
                ->default('')
                ->comment('the prefix for the database tables');
            $table->string('db_engine')
                ->default('InnoDB')
                ->comment('the storage engine for the database tables');
            $table->timestamps();
            $table->softDeletes()
                ->comment('soft delete column to mark the connection as deleted without removing it from the database');
            $table->comment('This table stores the database connection details for each tenant.');

            $table->unique(['tenant_uuid', 'db_name'], 'tenant_db_unique')
                ->comment('unique constraint to ensure a tenant can only have one database with a specific name');
            $table->index('db_name', 'db_name_index')
                ->comment('index to speed up queries filtering by database name');
            $table->index('db_host', 'db_host_index')
                ->comment('index to speed up queries filtering by database host');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};
