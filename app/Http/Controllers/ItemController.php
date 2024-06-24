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

/**
 * @group Item
 *
 * APIs for managing items
 * @authenticated
 */
class ItemController extends Controller
{
    use HttpResponses;

    /**
     * Get items
     *
     * Get a list of items
     *
     * @queryParam page_size integer The number of items to display per page. Example: 15
     * @queryParam page integer The page number. Example: 1
     * @queryParam warehouse_id integer The id of the warehouse. Example: 1
     * @queryParam material_id integer The id of the material. Example: 1
     * @queryParam name string The name of the item. Example: item
     */
    public function index(Request $request)
    {
        $query = $request->query();

        $items = Item::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return ItemResource::collection($items);
    }

    /**
     * Add item
     *
     * Add a new item
     *
     * @bodyParam warehouse_id integer required The id of the warehouse. Example: 1
     * @bodyParam material_id integer required The id of the material. Example: 1
     * @bodyParam name string required The name of the item. Example: item
     * @bodyParam max_stock integer required The maximum stock of the item. Example: 50
     * @bodyParam image file The image of the item.
     *
     * @subgroup Management
     */
    public function store(ItemStoreRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $item = Item::create([
                'warehouse_id' => $validated['warehouse_id'],
                'material_id' => $validated['material_id'],
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'stock' => $validated['max_stock'],
                'max_stock' => $validated['max_stock'],
            ]);

            if (isset($validated['image'])) {
                $image = $validated['image'];
                $directory = 'items/images/';
                $image->storeAs('public/' . $directory, $image->hashName(), 'local');
                $image_url = url('storage/' . $directory . $image->hashName());

                $item->update(['image' => $image_url]);
            }
        });

        $item = Item::latest()->first();


        return $this->success('Item created successfully', new ItemResource($item), 201);
    }

    /**
     * Update item
     *
     * @subgroup Management
     */
    public function update(ItemUpdateRequest $request, Item $item)
    {
        Gate::authorize('management');

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $item) {
            $item->update([
                'warehouse_id' => $validated['warehouse_id'] ?? $item->warehouse_id,
                'material_id' => $validated['material_id'] ?? $item->material_id,
                'category_id' => $validated['category_id'] ?? $item->category_id,
                'name' => $validated['name'] ?? $item->name,
            ]);

            if (isset($validated['image'])) {
                $image = $validated['image'];
                $directory = 'items/images/';
                $image->storeAs('public/' . $directory, $image->hashName(), 'local');
                $image_url = url('/storage/' . $directory . $image->hashName());

                Storage::disk('local')->delete('public/' . $directory . basename($item->image));

                $item->update(['image' => $image_url]);
            }
        });

        return $this->success('Item updated successfully', new ItemResource($item->refresh()), 201);
    }

    /**
     * Delete item
     *
     * @urlParam item required The id of the item. Example: 1
     *
     * @subgroup Management
     */
    public function delete(Item $item)
    {
        Gate::authorize('management');

        $item->delete();

        return $this->success('Item deleted successfully', null, 200);
    }
}
