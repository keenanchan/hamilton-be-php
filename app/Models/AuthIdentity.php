<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class AuthIdentity extends Model
{
    use UsesUuid;

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array<string>|bool
    */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    /**
     * Bootstrap the model / all traits.
     */
    protected static function boot()
    {
        parent::boot();
        static::bootUsesUuid();  // assign UUID if a fixed ID hasn't already been set
    }

    /**
     * Many-to-one relation with Users (each AuthIdentity should belong to one User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}