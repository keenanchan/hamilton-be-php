<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Simple interface (trait) enforcing a model to use a UUID as id.
 * We separate this into a trait as we foresee many models using UUIDs as IDs.
 */
trait UsesUuid
{
    protected static function bootUsesUuid(): void
    {
        static::creating(function ($model) {
            // Only assign if not already set, to allow seeding w/ fixed IDs if needed!
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }
}