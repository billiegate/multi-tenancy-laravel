<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->timestamps();
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
