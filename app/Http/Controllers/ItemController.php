<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        $query = $request->query();

        $items = Item::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return ItemResource::collection($items);
    }

    public function store(ItemStoreRequest $request)
    {
        $validated = $request->validated();

        $item = Item::create([
            'warehouse_id' => $validated['warehouse_id'],
            'material_id' => $validated['material_id'],
            'name' => $validated['name'],
            'stock' => $validated['max_stock'],
            'max_stock' => $validated['max_stock'],
        ]);

        return $this->success('Item created successfully', new ItemResource($item), 201);
    }
}
