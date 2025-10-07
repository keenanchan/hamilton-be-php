<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * EXPECTED_SCHEMA (roles)
 * - id: uuid, PK
 * - slug: string UNIQUE (e.g., admin, worker, resident) // We use this for indexes/html
 * - name: string
 * - created_at, updated_at
 * Indexes:
 * - roles_slug_unique
 * 
 * SCHEMA_CHANGES (users)
 * - FOREIGN_KEY: users.role_id -> roles.id
 */
return new class extends Migration {
    public function up(): void
    {
        // roles table creation
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();
        });

        // foreign key: users.role_id -> roles.id
        Schema::table('users', function(Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
        Schema::dropIfExists('roles');
    }
};