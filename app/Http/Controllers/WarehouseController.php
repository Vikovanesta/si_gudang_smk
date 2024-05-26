<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WarehouseController extends Controller
{
    use HttpResponses;
    
    public function index()
    {
        $query = request()->query();

        $warehouses = Warehouse::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return WarehouseResource::collection($warehouses);
    }

    public function store(Request $request)
    {
        Gate::authorize('management');

        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $warehouse = Warehouse::create($validated);

        return $this->success(new WarehouseResource($warehouse), 'Warehouse created successfully', 201);
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        Gate::authorize('management');

        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $warehouse->update($validated);

        return $this->success(new WarehouseResource($warehouse->refresh()), 'Warehouse updated successfully', 201);
    }

    public function delete(Warehouse $warehouse)
    {
        Gate::authorize('management');

        $warehouse->delete();
        
        return $this->success(null, 'Warehouse deleted successfully');
    }
}
