<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

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

        DB::transaction(function () use ($validated) {
            $item = Item::create([
                'warehouse_id' => $validated['warehouse_id'],
                'material_id' => $validated['material_id'],
                'name' => $validated['name'],
                'stock' => $validated['max_stock'],
                'max_stock' => $validated['max_stock'],
            ]);

            if (isset($validated['image'])) {
                $image = $validated['image'];
                $directory = 'items/images';
                $image->storeAs('public/' . $directory, $image->hashName(), 'local');
                $image_url = url('storage/' . $directory . $image->hashName());

                $item->update(['image' => $image_url]);
            }
        });

        $item = Item::latest()->first();


        return $this->success('Item created successfully', new ItemResource($item), 201);
    }

    public function update(ItemUpdateRequest $request, Item $item)
    {
        Gate::authorize('management');

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $item) {
            $item->update([
                'warehouse_id' => $validated['warehouse_id'] ?? $item->warehouse_id,
                'material_id' => $validated['material_id'] ?? $item->material_id,
                'name' => $validated['name'] ?? $item->name,
            ]);

            if (isset($validated['image'])) {
                $image = $validated['image'];
                $directory = 'items/images';
                $image->storeAs('public/' . $directory, $image->hashName(), 'local');
                $image_url = url('/storage/' . $directory . $image->hashName());

                Storage::disk('local')->delete('public/' . $directory . basename($item->image));

                $item->update(['image' => $image_url]);
            }
        });

        return $this->success('Item updated successfully', new ItemResource($item->refresh()), 201);
    }

    public function delete(Item $item)
    {
        Gate::authorize('management');

        $item->delete();

        return $this->success('Item deleted successfully', null, 200);
    }
}
