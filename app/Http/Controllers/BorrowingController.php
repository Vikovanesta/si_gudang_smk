<?php

namespace App\Http\Controllers;

use App\Http\Resources\BorrowingResource;
use App\Models\Borrowing;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BorrowingController extends Controller
{
    use HttpResponses;

    public function indexAcademic(Request $request)
    {
        Gate::authorize('academic');

        $query = $request->query();

        $borrowings = Borrowing::whereHas('request', function ($query) use ($request) {
            $query->where('sender_id', Auth::id());
        })->filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return BorrowingResource::collection($borrowings);
    }

    public function indexManagement(Request $request)
    {
        Gate::authorize('management');

        $query = $request->query();

        $borrowings = Borrowing::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return BorrowingResource::collection($borrowings);
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        Gate::authorize('management');

        $validated = $request->validate([
            'borrowed_at' => 'nullable|date_format:Y-m-d H:i:s',
            'returned_at' => 'nullable|date_format:Y-m-d H:i:s',
            'is_cancelled' => 'nullable|boolean',
        ]);

        $borrowing->update($validated);
        $borrowing->refresh();

        if (isset($validated['is_cancelled']) && $validated['is_cancelled']){
            $borrowing->request->requestDetail->update(['status_id' => 4]); // 'Cancelled'
        }

        if ($borrowing->borrowed_at && $borrowing->returned_at) {
            $borrowing->update(['status_id' => 3]); // 'Returned'
        } elseif ($borrowing->borrowed_at) {
            $borrowing->update(['status_id' => 2]); // 'Borrowed'
        } else {
            $borrowing->update(['status_id' => 1]); // 'Pending'
        }

        return $this->success(
            new BorrowingResource($borrowing),
            'Borrowing has been updated',
            201
        );
    }
}
