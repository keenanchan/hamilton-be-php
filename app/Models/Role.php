<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
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
     * Many-to-many relation with permissions.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * One-to-many relation with users (one role has many users)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}