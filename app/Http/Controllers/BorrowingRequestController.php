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
use Illuminate\Support\Facades\Gate;

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

    public function handle(Request $request, BorrowingRequest $borrowingRequest)
    {
        if ($borrowingRequest->isHandled()) {
            return $this->error(
                null,
                'This borrowing request has been handled',
                403
            );
        }


        if (Gate::allows('management') && !$borrowingRequest->is_revised) {
            return $this->handleManagement($request, $borrowingRequest);
        }
        elseif (Gate::allows('academic') && $borrowingRequest->is_revised) {
            return $this->handleAcademic($request, $borrowingRequest);
        }
        else {
            return $this->error(
                null,
                'You are not authorized to handle this borrowing request',
                403
            );
        }
    }

    private function handleManagement(Request $request, BorrowingRequest $borrowingRequest)
    {
        $validated = $request->validate([
            'status' => 'required|int|in:1,2,3', // 1: Approved, 2: Rejected, 3: Revised
            'start_date' => 'exclude_unless:status,3|required_without_all:end_date,borrowed_items|date_format:Y-m-d H:i:s|after_or_equal:today' ?? $borrowingRequest->details->last()->start_date,
            'end_date' => 'exclude_unless:status,3|required_without_all:start_date,borrowed_items|date_format:Y-m-d H:i:s|after:start_date' ?? $borrowingRequest->details->last()->end_date,
            'borrowed_items' => 'exclude_unless:status,3|required_without_all:start_date,end_date|json' ?? $borrowingRequest->details->last()->borrowedItems->map(function ($borrowedItem) {
                return [
                    'item_id' => $borrowedItem->item_id,
                    'quantity' => $borrowedItem->quantity,
                ];
            }),
            'note' => 'required_if:status,3|string',
        ]);

        $borrowingRequest->update([
            'handler_id' => Auth::id(),
        ]);

        if ($validated['status'] == 1) {
            $borrowingRequest->details->last()->update([
                'status_id' => 2, // 'Approved'
            ]);
        }
        elseif ($validated['status'] == 2) {
            $borrowingRequest->details->last()->update([
                'status_id' => 3, // 'Rejected'
            ]);
        }
        elseif ($validated['status'] == 3){
            $borrowingRequest->update([
                'is_revised' => true,
            ]);

            $borrowingRequest->details->last()->update([
                'status_id' => 4, // 'Revised'
            ]);

            $requestDetail = RequestDetail::create([
                'request_id' => $borrowingRequest->id,
                'status_id' => 1, // 'Pending'
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'note' => $validated['note'] ?? null,
                'is_revision' => true,
            ]);

            $borrowedItems = json_decode($validated['borrowed_items'], true);
            foreach ($borrowedItems as $borrowedItem) {
                BorrowedItem::create([
                    'request_detail_id' => $requestDetail->id,
                    'item_id' => $borrowedItem['item_id'],
                    'quantity' => $borrowedItem['quantity'],
                ]);
            }
        }

        return $this->success(
            new BorrowingRequestResource($borrowingRequest->fresh()),
            'Borrowing request updated successfully',
            201
        );
    }

    private function handleAcademic(Request $request, BorrowingRequest $borrowingRequest)
    {
        Gate::authorize('handle-academic-request', $borrowingRequest);

        $validated = $request->validate([
            'is_approved' => 'required|boolean',
        ]);

        $borrowingRequest->details->last()->update([
            'status_id' => $validated['is_approved'] ? 2 : 3, // 2: Approved, 3: Rejected
        ]);

        return $this->success(
            new BorrowingRequestResource($borrowingRequest),
            'Borrowing request updated successfully',
            201
        );
    }
}
