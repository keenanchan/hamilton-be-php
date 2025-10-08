<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * JSON representation of a user. We handle user representation matters
 * in this file to avoid code bloat.
 */
class UserResource extends JsonResource
{
    // return id, name, role, permissions (latter two through accessors)
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'role'        => optional($this->role)->slug,
            'permissions' => optional($this->role)->permissions->pluck('slug'),
        ];
    }
}