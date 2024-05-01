<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowingRequestStoreRequest;
use App\Http\Resources\BorrowingRequestResource;
use App\Models\BorrowedItem;
use App\Models\BorrowingRequest;
use App\Models\RequestDetail;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingRequestController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        $query = $request->query();

        $borrowingRequests = BorrowingRequest::where('sender_id', Auth::id())
            ->filterByQuery($query)
            ->paginate($query['page_size'] ?? 15);

        return BorrowingRequestResource::collection($borrowingRequests);
    }

    public function store(BorrowingRequestStoreRequest $request)
    {
        $validated = $request->validated();

        $borrowingRequest = BorrowingRequest::create([
            'sender_id' => Auth::id(),
            'purpose' => $validated['purpose'],
        ]);

        $requestDetail = RequestDetail::create([
            'request_id' => $borrowingRequest->id,
            'status_id' => 1, // 'Pending
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        $borrowedItems = json_decode($validated['borrowed_items'], true);
        foreach ($borrowedItems as $borrowedItem) {
            BorrowedItem::create([
                'request_detail_id' => $requestDetail->id,
                'item_id' => $borrowedItem['item_id'],
                'quantity' => $borrowedItem['quantity'],
            ]);
        }

        return $this->success(
            new BorrowingRequestResource($borrowingRequest),
            'Borrowing request created successfully',
            201
        );
    }
}
