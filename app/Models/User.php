<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Concerns\UsesUuid;

class User extends Authenticatable
{
    use HasApiTokens, UsesUuid, Notifiable;

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array<string>|bool
    */
    protected $guarded = [];

    /**
     * Bootstrap the model / all traits.
     */
    protected static function boot()
    {
        parent::boot();
        static::bootUsesUuid();  // assign UUID if a fixed ID hasn't already been set
    }

    /**
     * One-to-many relation with AuthIdentities (one user has many auth identities)
     */
    public function identities()
    {
        return $this->hasMany(AuthIdentity::class);
    }

    /**
     * Many-to-one relation with Roles (each user has only one role)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Accessor for permissions
     * Technically this is a many-to-many relation through roles
     * collect(null) here represents a null collection; this is to prevent
     * errors arising from later uses of array fns on null.
     * We recommend this convention for accessors, for null safety
     */
    public function permissions()
    {
        return $this->role ? $this->role->permissions() : collect(null);
    }

    /**
     * Utility for permission-checking
     * This is useful for checking whether a user has the permission
     * to invoke a particular API call
     */
    public function hasPermission(string $slug): bool
    {
        // eager loading of permissions to avoid n+1 query problem
        $role = $this->role()->with('permissions')->first();
        if (! $role) return false;
        return $role->permissions->contains('slug', $slug);
    }
}
