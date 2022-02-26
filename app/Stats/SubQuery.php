<?php

namespace App\Stats;

interface SubQuery
{
    public function __construct();
    public function filter(RequestFilters $requestFilters): self;
}
