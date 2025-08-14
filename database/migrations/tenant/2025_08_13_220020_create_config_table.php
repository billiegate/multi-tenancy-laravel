<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration to create the 'configs' table for tenant configurations.
 * This table will store configuration settings for each tenant, allowing for dynamic configuration management.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(DB::raw('(UUID())'))
                ->comment('string representation to uniquely identify this entity');
            $table->string('key')
                ->unique()
                ->comment('the key for the configuration setting');
            $table->text('value')
                ->comment('the value for the configuration setting');
            $table->enum('type', ['string', 'integer', 'boolean', 'json'])
                ->default('string')
                ->comment('the type of the configuration setting');
            $table->string('description')
                ->nullable()
                ->default('')
                ->comment('a brief description of the configuration setting');
            $table->boolean('is_active')
                ->default(true)
                ->comment('indicates if the configuration setting is active');
            $table->boolean('is_encrypted')
                ->default(false)
                ->comment('indicates if the configuration value is encrypted');
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete()
                ->comment('the user who created this configuration setting');
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete()
                ->comment('the user who last updated this configuration setting');
            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete()
                ->comment('the user who deleted this configuration setting');
            $table->softDeletes()
                ->comment('soft delete column to mark the configuration setting as deleted without removing it from the database');
            $table->comment('This table stores configuration settings for each tenant, allowing for dynamic configuration management.');
            $table->index('key', 'idx_key')
                ->comment('index to speed up queries filtering by configuration key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};
