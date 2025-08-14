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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(DB::raw('(UUID())'))
                ->comment('string representation to uniquely identify this entity');
            $table->uuid('tenant_uuid')
                ->comment('string representation to uniquely identify this entity');
            $table->string('logo')
                ->nullable()
                ->default('https://via.placeholder.com/150')
                ->comment('the url to where the logo is stored');
            $table->string('website')
                ->nullable()
                ->default('https://example.com')
                ->comment('the website of the tenant');
            $table->string('email')
                ->nullable()
                ->default('')
                ->comment('the email of the tenant');
            $table->string('phone')
                ->nullable()
                ->default('')
                ->comment('the phone number of the tenant');
            $table->string('address')
                ->nullable()
                ->default('')
                ->comment('the address of the tenant');
            $table->string('country')
                ->nullable()
                ->default('Nigeria')
                ->comment('the country of the tenant');
            $table->string('currency')
                ->nullable()
                ->default('NGN')
                ->comment('the currency of the tenant');
            $table->string('timezone')
                ->nullable()
                ->default('Africa/Lagos')
                ->comment('the timezone of the tenant');
            $table->string('locale')
                ->nullable()
                ->default('en')
                ->comment('the locale of the tenant');
            $table->string('color_primary')
                ->nullable()
                ->default('#3490dc')
                ->comment('the primary color of the tenant');
            $table->string('color_secondary')
                ->nullable()
                ->default('#ffed4a')
                ->comment('the secondary color of the tenant');
            $table->string('color_accent')
                ->nullable()
                ->default('#e3342f')
                ->comment('the accent color of the tenant');
            $table->timestamps();

            $table->softDeletes()
                ->comment('soft delete column to mark the brand as deleted without removing it from the database');
            $table->comment('brands table to store tenant brand information');
            $table->string('created_by')
                ->nullable()
                ->default('system')
                ->comment('the user who created the brand');

            $table->unique(['tenant_uuid', 'uuid'], 'unique_tenant_brand');
            $table->index('tenant_uuid', 'idx_tenant_uuid');
            $table->index('uuid', 'idx_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
