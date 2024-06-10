<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * @group Warehouse
 *
 * APIs for managing warehouses
 *
 * @authenticated
 */
class WarehouseController extends Controller
{
    use HttpResponses;
    
    /**
     * Get warehouses
     * 
     * Get a list of warehouses
     * 
     * @queryParam name string The name of the warehouse. Example: warehouse
     * @queryParam item_id integer The id of the item. Example: 2
     * @queryParam page integer The page number. Example: 1
     * @queryParam page_size integer The number of warehouses to display per page. Example: 15
     * @queryParam sort_by string The column to sort by. Example: name
     * @queryParam sort_direction string The direction to sort. Example: asc
     */
    public function index()
    {
        $query = request()->query();

        $warehouses = Warehouse::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return WarehouseResource::collection($warehouses);
    }

    /**
     * Add Warehouse
     * 
     * Add a new warehouse
     * 
     * @bodyParam name string required The name of the warehouse. Example: warehouse
     * 
     * @subgroup Management
     */
    public function store(Request $request)
    {
        Gate::authorize('management');

        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $warehouse = Warehouse::create($validated);

        return $this->success(new WarehouseResource($warehouse), 'Warehouse created successfully', 201);
    }

    /**
     * Uodate Warehouse
     * 
     * Update a warehouse
     * 
     * @urlParam warehouse required The ID of the warehouse. Example: 1
     * @bodyParam name string required The name of the warehouse. Example: warehouse
     * 
     * @subgroup Management
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        Gate::authorize('management');

        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $warehouse->update($validated);

        return $this->success(new WarehouseResource($warehouse->refresh()), 'Warehouse updated successfully', 201);
    }


    /**
     * Delete Warehouse
     * 
     * Delete a warehouse
     * 
     * @urlParam warehouse required The ID of the warehouse. Example: 1
     */
    public function delete(Warehouse $warehouse)
    {
        Gate::authorize('management');

        $warehouse->delete();
        
        return $this->success(null, 'Warehouse deleted successfully');
    }
}
