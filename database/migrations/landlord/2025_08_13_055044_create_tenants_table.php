<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Migration to create the tenants table.
 *
 * This table stores the basic information about each tenant in a multi-tenant application.
 * It includes fields for tenant identification, status, API keys, and other relevant details.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(DB::raw('(UUID())'))
                ->comment('string representation to uniquely identify this entity');
            $table->string('name')
                ->comment('the name of the tenant');
            $table->string('subdomain')
                ->unique()
                ->comment('the subdomain of the tenant');
            $table->boolean('is_active')
                ->default(true)
                ->comment('indicates whether the tenant is active or not');
            $table->boolean('is_verified')
                ->default(false)
                ->comment('indicates whether the tenant is verified or not');
            $table->boolean('is_suspended')
                ->default(false)
                ->comment('indicates whether the tenant is suspended or not');
            $table->boolean('is_deleted')
                ->default(false)
                ->comment('indicates whether the tenant is deleted or not');
            $table->boolean('is_live')
                ->default(false)
                ->comment('indicates whether the tenant is live or not');
            $table->string('test_secret_key')
                ->nullable()
                ->default('test-sk-' . Str::uuid()->toString())
                ->comment('the test secret key for the tenant');
            $table->string('test_api_key')
                ->nullable()
                ->default('test-pk-' . Str::uuid()->toString())
                ->comment('the test public key for the tenant');
            $table->string('api_key')
                ->nullable()
                ->default('pk-' . Str::uuid()->toString())
                ->comment('the API key for the tenant');
            $table->string('api_secret')
                ->nullable()
                ->default('sk-' . Str::uuid()->toString())
                ->comment('the production secret key for the tenant');
            $table->timestamps();
            $table->softDeletes()
                ->comment('soft delete column to mark the tenant as deleted without removing it from the database');
            $table->unique(['subdomain', 'uuid'], 'tenant_subdomain_unique')
                ->comment('unique constraint to ensure a tenant can only have one subdomain with a specific UUID');
            $table->index('uuid', 'tenant_uuid_index')
                ->comment('index to speed up queries on the tenant UUID');
            $table->index('subdomain', 'tenant_subdomain_index')
                ->comment('index to speed up queries on the tenant subdomain');
            $table->comment('This table stores the basic information about each tenant.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
