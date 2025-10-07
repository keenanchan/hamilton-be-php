<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * We separate the users table from the auth_identities table
 * in the recognition that users may have different methods of sign-on;
 * though residents may sign on with room numbers and admins may sign on
 * with emails, they are all users and there is no reason to store their
 * info in different places.
 * 
 * EXPECTED_SCHEMA (auth_identities)
 * - id: uuid, PK
 * - user_id: uuid FK -> users(id)
 * - type: enum('email','username','room_no') // to be extended in future if SSO is implemented
 * - identifier: string (email addr, username, or room number)
 * - password_hash: string (argon2id/bcrypt via Hash::make)
 * - last_login_at: timestamp nullable
 * - created_at, updated_at
 * Constraints:
 * - UNIQUE(user_id, type)
 * - UNIQUE(type, identifier)
 * Indexes:
 * - auth_identities_user_id_foreign
 * - auth_identities_type_identifier_unique
 * - auth_identities_user_id_type_unique
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('auth_identities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->enum('type', ['email', 'username', 'room_no']);
            $table->string('identifier');
            $table->string('password_hash');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();

            // foreign key: auth_identities.user_id -> users.id
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            // uniqueness constraints: unique identifiers per type, only one of each type per user
            $table->unique(['type', 'identifier']);
            $table->unique(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_identities');
    }
};