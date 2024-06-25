<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemCategoryResource;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ItemCategoryController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        $query = $request->query();

        $categories = ItemCategory::filterByQuery($query)->paginate($query['page_size'] ?? 15);
        return ItemCategoryResource::collection($categories);
    }

    public function store(Request $request)
    {
        Gate::authorize('management');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = ItemCategory::create($validated);
        return $this->success(new ItemCategoryResource($category), 'Item category added successfully', 201);
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('management');

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        $category = ItemCategory::find($id);

        $category->update($validated);
        return $this->success(new ItemCategoryResource($category->refresh()), 'Item category updated successfully,', 201);
    }

    public function destroy($id)
    {
        Gate::authorize('management');

        $category = ItemCategory::find($id);

        $category->delete();
        return $this->success(null, 'Item category deleted successfully', 200);
    }
}
