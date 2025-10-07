<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use UsesUuid;

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
     * Many-to-many relation with roles.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}