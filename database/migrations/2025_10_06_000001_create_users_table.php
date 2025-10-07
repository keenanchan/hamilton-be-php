<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * EXPECTED_SCHEMA (users)
 * - id: uuid, PK
 * - role_id: uuid, nullable FK -> roles(id)
 * - name: string (display name)
 * - created_at, updated_at: timestamps
 * Indexes:
 * - users_role_id_foreign
 * - users_email_unique (nullable unique)
 */
return new class extends Migration {
    public function up(): void
    {
        // users table creation
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('role_id')->nullable();  // set foreign key after roles table exists
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};