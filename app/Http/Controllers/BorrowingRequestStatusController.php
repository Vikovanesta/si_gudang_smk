<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequestStatus;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class BorrowingRequestStatusController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        $borrowingRequestStatuses = BorrowingRequestStatus::all();
        return $this->success($borrowingRequestStatuses, 'Borrowing request statuses retrieved successfully.');
    }
}
