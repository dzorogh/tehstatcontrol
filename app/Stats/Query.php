<?php

namespace App\Stats;

use Illuminate\Database\Eloquent\Builder;

class Query
{
    public Builder $query;

    public function get(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->query->get();
    }
}
