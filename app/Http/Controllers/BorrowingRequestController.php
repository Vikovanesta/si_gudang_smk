<?php

namespace App\Http\Controllers;

use App\Http\Resources\BorrowingRequestResource;
use App\Models\BorrowingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query();

        $borrowingRequests = BorrowingRequest::where('sender_id', Auth::id())
            ->filterByQuery($query)
            ->paginate($query['page_size'] ?? 15);

        return BorrowingRequestResource::collection($borrowingRequests);
    }
}
