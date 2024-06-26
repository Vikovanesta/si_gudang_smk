<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchoolSubjectResource;
use App\Models\SchoolSubject;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class SchoolSubjectController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        $schoolSubjects = SchoolSubject::all();

        return $this->success(SchoolSubjectResource::collection($schoolSubjects), 'School subjects retrieved successfully');
    }
}
