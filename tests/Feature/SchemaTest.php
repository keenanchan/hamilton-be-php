<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchemaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_table_matches_contract()
    {
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasColumns('users', [
            'id',
            'role_id',
            'name',
            'email',
            'created_at',
            'updated_at'
        ]));
    }

    /** @test */
    public function roles_table_matches_contract()
    {
        $this->assertTrue(Schema::hasTable('roles'));
        $this->assertTrue(Schema::hasColumns('roles', [
            'id',
            'slug',
            'name',
            'created_at',
            'updated_at',
        ]));
    }

    /** @test */
    public function permissions_table_matches_contract()
    {
        $this->assertTrue(Schema::hasTable('permissions'));
        $this->assertTrue(Schema::hasColumns('permissions', [
            'id',
            'slug',
            'name',
            'created_at',
            'updated_at',
        ]));
    }

    /** @test */
    public function role_permission_pivot_matches_contract()
    {
        $this->assertTrue(Schema::hasTable('role_permission'));
        $this->assertTrue(Schema::hasColumns('role_permission', [
            'role_id',
            'permission_id',
        ]));
    }

    /** @test */
    public function auth_identities_table_matches_contract()
    {
        $this->assertTrue(Schema::hasTable('auth_identities'));
        $this->assertTrue(Schema::hasColumns('auth_identities', [
            'id',
            'user_id',
            'type',
            'identifier',
            'password_hash',
            'last_login_at',
            'created_at',
            'updated_at',
        ]));
    }
};