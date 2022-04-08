<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PageResource;
use App\Models\Category;
use App\Models\Group;
use App\Models\Page;

class PageController extends Controller
{
    public function show(Page $page): PageResource
    {
        return new \App\Http\Resources\PageResource($page->load('category'));
    }

    public function news(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return PageResource::collection(Page::whereNews(true)->with('category')->get());
    }

    public function newsTypes()
    {
        $categoriesIds = Page::whereNews(true)
            ->whereNotNull('category_id')
            ->groupBy('category_id')
            ->get('category_id')
            ->pluck('category_id');

        $categories = Category::query()
            ->whereIn('id', $categoriesIds)
            ->orderBy('order')
            ->orderBy('title')
            ->get();

        return GroupResource::collection($categories);
    }
}
