<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowedItemUpdateRequest;
use App\Http\Resources\BorrowedItemResource;
use App\Models\BorrowedItem;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BorrowedItemController extends Controller
{
    use HttpResponses;

    public function indexAcademic(Request $request)
    {
        Gate::authorize('academic');

        $query = $request->query();

        $borrowedItems = BorrowedItem::filterByQuery($query)
            ->whereHas('requestDetail.request', function ($q) {
                $q->where('sender_id', Auth::id());
            })
            ->paginate($query['page_size'] ?? 15);

        return BorrowedItemResource::collection($borrowedItems);
    }

    public function indexManagement(Request $request)
    {
        Gate::authorize('management');

        $query = $request->query();

        $borrowedItems = BorrowedItem::filterByQuery($query)
            ->paginate($query['page_size'] ?? 15);

        return BorrowedItemResource::collection($borrowedItems);
    }

    public function update(BorrowedItemUpdateRequest $request, BorrowedItem $borrowedItem)
    {
        Gate::authorize('update-borrowed-item', $borrowedItem);

        $validated = $request->validated();

        DB::transaction(function () use ($borrowedItem, $validated) {
            $borrowedItem->update(
                [
                    'returned_quantity' => $borrowedItem->returned_quantity + ($validated['returned_quantity'] ?? 0),
                    'is_cancelled' => $validated['is_cancelled'] ?? false,
                ]
            );
            
            $borrowedItem->item->update(['stock' => $borrowedItem->item->quantity - ($validated['returned_quantity'] ?? 0)]);
            $borrowedItem->refresh();
            
            if ($borrowedItem->quantity == $borrowedItem->returned_quantity) {
                $borrowedItem->returned_at = now();
                $borrowedItem->save();
            }
            
            if (isset($validated['is_cancelled']) && $validated['is_cancelled']) {
                $borrowedItem->item->increment('quantity', $borrowedItem->quantity - $borrowedItem->returned_quantity);
            }
            
            if (isset($validated['is_borrowed']) && $validated['is_borrowed']) {
                $borrowedItem->borrowed_at = now();
            }
            
            if (isset($validated['returned_at'])) {
                $borrowedItem->returned_quantity = $borrowedItem->quantity;
                $borrowedItem->save();
            }

            $borrowedItem->update(
                [
                    'borrowed_at' => $validated['borrowed_at'] ?? $borrowedItem->borrowed_at,
                    'returned_at' => $validated['returned_at'] ?? $borrowedItem->returned_at,
                ]
            );
        });
        
        
        return $this->success(
            new BorrowedItemResource($borrowedItem), 
            'Borrowed item updated successfully',
            201);
    }
}
