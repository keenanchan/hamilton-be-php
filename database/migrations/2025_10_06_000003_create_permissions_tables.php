<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


/**
 * EXPECTED_SCHEMA (permissions)
 * - id: uuid, PK
 * - slug: string UNIQUE (e.g., user.read, user.write)  // Used for indexes/html
 * - name: string
 * - created_at, updated_at
 *
 * EXPECTED_SCHEMA (role_permission)
 * - role_id: uuid FK -> roles(id)
 * - permission_id: uuid FK -> permissions(id)
 * - PK: (role_id, permission_id)
 * Indexes:
 * - role_permission_role_id_permission_id_primary (composite PK)
 */
return new class extends Migration {
    public function up(): void
    {
        // permissions table creation
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('name');
            $table->timestamps();
        });

        // role_permission pivot table creation
        Schema::create('role_permission', function (Blueprint $table) {
            $table->uuid('role_id');
            $table->uuid('permission_id');
            $table->primary(['role_id', 'permission_id']);

            // keys cascade on delete; that is, associated child records are deleted on delete
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
    }
};