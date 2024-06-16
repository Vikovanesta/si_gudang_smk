<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassResource;
use App\Models\SchoolClass;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        $schoolClasses = SchoolClass::all();

        return $this->success(ClassResource::collection($schoolClasses), 'School classes retrieved successfully');
    }
}
