<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageResource;
use App\Models\Page;

class PageController extends Controller
{
    public function show(Page $page): PageResource
    {
        return new \App\Http\Resources\PageResource($page);
    }

    public function news(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return PageResource::collection(Page::whereNews(true)->get());
    }
}
