<?php

namespace Tests\Feature;

use App\Models\AuthIdentity;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup function
     * Creates a user with a role with a permission,
     * provisions an auth identity for that user,
     * and inserts into the relevant databases.
     */
    protected function seedUser(
        string $type,
        string $identifier,
        string $password,
        string $roleSlug = 'worker'
    ): User
    {
        $role = Role::create([
            'slug' => $roleSlug,
            'name' => ucfirst($roleSlug)
        ]);
        $permission = Permission::create([
            'slug' => 'orders.view',
            'name' => 'View orders'
        ]);
        $role->permissions()->attach($perm->id);

        $user = User::create([
            'name'    => 'Test User',
            'role_id' => $role->id
        ]);

        AuthIdentity::create([
            'user_id'       => $u->id,
            'type'          => $type,
            'identifier'    => $identifier,
            'password_hash' => Hash::make($password),
        ]);

        return $user;
    }

    /** @test */
    public function login_with_email_identity()
    {
        $this->seedUser(
            'email',
            'admin@example.com',
            'StrongPassword123',
            'admin',
        );

        // login with seeded user
        $res = $this->postJson('/api/login', [
            'type'       => 'email',
            'identifier' => 'admin@example.com',
            'password'   => 'StrongPassword123',
        ])->assertStatus(200);

        // ensure correct structure
        $res->assertJsonStructure([
            'token',
            'user' => [
                'id',
                'name',
                'role',
                'permissions'
            ]
        ]);
    }

    /** @test */
    public function login_with_username_identity()
    {
        $this->seedUser(
            'username',
            'worker_john',
            'weakpassword',
            'kitchen',
        );

        // login with seeded user
        $res = $this->postJson('/api/login', [
            'type'       => 'username',
            'identifier' => 'worker_john',
            'password'   => 'weakpassword',
        ])->assertStatus(200);
    }

    /** @test */
    public function login_with_room_no_identity()
    {
        $this->seedUser(
            'room_no',
            '101',
            'wong',
            'resident',
        );

        // login with seeded user
        $res = $this->postJson('/api/login', [
            'type'       => 'room_no',
            'identifier' => '101',
            'password'   => 'wong',
        ])->assertStatus(200);
    }

    /** @test */
    public function invalid_credentials_are_rejected()
    {
        $this->seedUser(
            'email',
            'a@b.com',
            'correct_credentials',
        );

        $this->postJson('/api/login', [
            'type'       => 'email',
            'identifier' => 'x@y.com',
            'password'   => 'wong_credentials',
        ])->assertStatus(401);
    }
}