<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Group
 *
 * @mixin \Eloquent
 * @mixin IdeHelperGroup
 */
class Group extends Model
{
    use HasFactory;

    protected $table = 'stats_groups';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class, 'group_id');
    }
}
