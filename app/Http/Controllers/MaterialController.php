<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaterialResource;
use App\Models\Material;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MaterialController extends Controller
{
    use HttpResponses;

    public function index()
    {
        $query = request()->query();

        $materials = Material::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return MaterialResource::collection($materials);
    }

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

    public function delete(Material $material)
    {
        Gate::authorize('management');

        $material->delete();
        
        return $this->success(null, 'Material deleted successfully');
    }
}
