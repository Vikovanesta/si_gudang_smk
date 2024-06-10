<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaterialResource;
use App\Models\Material;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * @group Material
 *
 * APIs for managing materials
 *
 * @authenticated
 */
class MaterialController extends Controller
{
    use HttpResponses;

    /**
     * Get materials
     * 
     * Get a list of materials
     * 
     * @queryParam name string The name of the material. Example: material
     * @queryParam material_category_id integer The id of the material category. Example: 1
     * @queryParam page integer The page number. Example: 1
     * @queryParam page_size integer The number of materials to display per page. Example: 15
     * @queryParam sort_by string The column to sort by. Example: name
     * @queryParam sort_direction string The direction to sort. Example: asc
     */
    public function index()
    {
        $query = request()->query();

        $materials = Material::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return MaterialResource::collection($materials);
    }

    /**
     * Add Material
     * 
     * Add a new material
     * 
     * @bodyParam name string required The name of the material. Example: material
     * @bodyParam material_category_id integer required The id of the material category. Example: 1
     * 
     * @subgroup Management
     */
    public function store(Request $request)
    {
        Gate::authorize('management');

        $validated = $request->validate([
            'name' => 'required|string',
            'material_category_id' => 'required|exists:material_categories,id',
        ]);

        $material = Material::create($validated);

        return $this->success(new MaterialResource($material), 'Material created successfully', 201);
    }

    /**
     * Update Material
     * 
     * Update a material
     * 
     * @urlParam material required The ID of the material. Example: 1
     * @bodyParam name string The name of the material. Example: material
     * @bodyParam material_category_id integer The id of the material category. Example: 1
     * 
     * @subgroup Management
     */
    public function update(Request $request, Material $material)
    {
        Gate::authorize('management');

        $validated = $request->validate([
            'name' => 'nullable|string',
            'material_category_id' => 'nullable|exists:material_categories,id',
        ]);

        $material->update($validated);

        return $this->success(new MaterialResource($material->refresh()), 'Material updated successfully', 201);
    }

    /**
     * Delete Material
     * 
     * @urlParam material required The ID of the material. Example: 1
     * 
     * @subgroup Management
     */
    public function delete(Material $material)
    {
        Gate::authorize('management');

        $material->delete();
        
        return $this->success(null, 'Material deleted successfully');
    }
}
