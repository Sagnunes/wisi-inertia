<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObjectType extends Model
{
    /** @use HasFactory<\Database\Factories\ObjectTypeFactory> */
    use HasFactory;

    public function statuses(): HasMany
    {
        return $this->hasMany(Status::class);
    }
}
