<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query();

        $items = Item::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return ItemResource::collection($items);
    }
}
