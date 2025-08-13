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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(DB::raw('(UUID())'))
                ->comment('string representation to uniquely identify this entity');
            $table->string('name')
                ->unique()
                ->comment('the name of the tenant');
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
                ->default('test-' . DB::raw('(UUID())')->getValue(DB::connection()->getQueryGrammar()))
                ->comment('the test secret key for the tenant');
            $table->string('prod_secret_key')
                ->nullable()
                ->default('sk-' . DB::raw('(UUID())')->getValue(DB::connection()->getQueryGrammar()))
                ->comment('the production secret key for the tenant');
            $table->string('api_key')
                ->nullable()
                ->default('api-' . DB::raw('(UUID())')->getValue(DB::connection()->getQueryGrammar()))
                ->comment('the API key for the tenant');
            $table->string('api_secret')
                ->nullable()    
            $table->timestamps();
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
