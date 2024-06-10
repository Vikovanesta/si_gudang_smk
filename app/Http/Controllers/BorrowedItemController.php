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

/**
 * @group Borrowed Item
 * 
 * APIs for managing borrowed items
 * 
 * @authenticated
 */
class BorrowedItemController extends Controller
{
    use HttpResponses;

    /**
     * Get borrowed items
     * 
     * Get a list of currently borrowed items by current user
     *
     * @queryParam request_detail_id integer The id of the request detail. Example: 1 
     * @queryParam item_id integer The id of the item. Example: 1
     * @queryParam handler_id integer The id of the handler. Example: 1
     * @queryParam status string The status of the borrowed item. Example: returned
     * @queryParam request_status string The status of the request. Example: pending
     * @queryParam min_borrowed_at date The minimum borrowed date. Example: 2021-01-01
     * @queryParam max_borrowed_at date The maximum borrowed date. Example: 2021-01-01
     * @queryParam min_returned_at date The minimum returned date. Example: 2021-01-01
     * @queryParam max_returned_at date The maximum returned date. Example: 2021-01-01
     * @queryParam page integer The page number. Example: 1
     * @queryParam page_size integer The number of borrowed items to display per page. Example: 15
     * @queryParam sort_by string The column to sort by. Example: item_id
     * @queryParam sort_direction string The direction to sort. Example: asc
     * 
     * @subgroup Academic
     */
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

    /**
     * Get borrowed items
     * 
     * Get a list of borrowed items
     *
     * @queryParam request_detail_id integer The id of the request detail. Example: 1 
     * @queryParam item_id integer The id of the item. Example: 1
     * @queryParam sender_id integer The id of the sender. Example: 1
     * @queryParam handler_id integer The id of the handler. Example: 1
     * @queryParam status string The status of the borrowed item. Example: returned
     * @queryParam request_status string The status of the request. Example: pending
     * @queryParam min_borrowed_at date The minimum borrowed date. Example: 2021-01-01
     * @queryParam max_borrowed_at date The maximum borrowed date. Example: 2021-01-01
     * @queryParam min_returned_at date The minimum returned date. Example: 2021-01-01
     * @queryParam max_returned_at date The maximum returned date. Example: 2021-01-01
     * @queryParam page integer The page number. Example: 1
     * @queryParam page_size integer The number of borrowed items to display per page. Example: 15
     * @queryParam sort_by string The column to sort by. Example: item_id
     * @queryParam sort_direction string The direction to sort. Example: asc
     * 
     * @subgroup Management
     */
    public function indexManagement(Request $request)
    {
        Gate::authorize('management');

        $query = $request->query();

        $borrowedItems = BorrowedItem::filterByQuery($query)
            ->paginate($query['page_size'] ?? 15);

        return BorrowedItemResource::collection($borrowedItems);
    }

    /**
     * Get borrowed item details
     * 
     * Get details of a borrowed item
     * 
     * @urlParam borrowed_item required The ID of the borrowed item. Example: 1
     */
    public function show(BorrowedItem $borrowedItem)
    {
        Gate::authorize('view-borrowed-item', $borrowedItem);

        $borrowedItem->load([
            'requestDetail.request',
            'item',
        ]);

        return $this->success(new BorrowedItemResource($borrowedItem), 'Borrowed item retrieved successfully');
    }

    /**
     * Update borrowed item
     * 
     * Update a borrowed item
     * 
     * Choose one between returned_quantity , is_cancelled, is_borrowed or borrowed_at:
     * 
     * @urlParam borrowed_item required The ID of the borrowed item. Example: 1
     * @bodyParam returned_quantity integer The quantity of the item returned. Example: 1
     * @bodyParam is_cancelled boolean Set if want to update status to 'cancelled'. Example: true
     * @bodyParam is_borrowed boolean Set if want to update status to 'borrowed'. Example: true
     * @bodyParam borrowed_at date The borrowed date of the item. Example: 2021-01-01
     * @bodyParam returned_at date The returned date of the item. Example: 2021-01-01
     * 
     * @subgroup Management
     */
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
