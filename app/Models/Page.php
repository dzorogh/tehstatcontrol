<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Page
 *
 * @mixin IdeHelperPage
 */
class Page extends Model
{
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
