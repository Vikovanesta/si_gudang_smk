<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemCategoryResource;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        $categories = ItemCategory::all()->sortBy('name');
        return ItemCategoryResource::collection($categories);
    }
}
