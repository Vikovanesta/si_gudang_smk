<?php

namespace App\Http\Controllers;

use App\Http\Resources\BorrowingResource;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BorrowingController extends Controller
{
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
}
