<?php

namespace Tests\Unit;

use App\Models\AuthIdentity;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RelationshipsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_has_identities_and_a_role_with_permissions()
    {
        $role = Role::create([
            'slug' => 'admin',
            'name' => 'Admin'
        ]);
        $permission = Permission::create([
            'slug' => 'user.read',
            'name' => 'Read Users'
        ]);

        // attach permission to role
        $role->permissions()->attach($perm->id);

        $user = User::create([
            'name'    => 'Alice',
            'role_id' => $role->id
        ]);

        AuthIdentity::create([
            'user_id'       => $user->id,
            'type'          => 'email',
            'identifier'    => 'john@example.com',
            'password_hash' => Hash::make('secret123'),  
        ]);

        $this->assertCount(1, $user->identities);
        $this->assertTrue($user->hasPermission('user.read'));
        $this->assertFalse($user->hasPermission('user.write'));
    }
}