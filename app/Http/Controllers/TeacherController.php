<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherStoreRequest;
use App\Http\Requests\TeacherUpdateRequest;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * @group Teacher
 * 
 * APIs for managing teachers
 * 
 * @authenticated
 */
class TeacherController extends Controller
{
    use HttpResponses;

    /**
     * Get teachers
     * 
     * Get a list of teachers
     * 
     * @queryParam name string The name of the teacher. Example: teacher
     * @queryParam nip string The nip of the teacher. Example: 123456789
     * @queryParam subject_id integer The id of the subject. Example: 1
     * @queryParam page integer The page number. Example: 1
     * @queryParam page_size integer The number of teachers to display per page. Example: 15
     * @queryParam sort_by string The column to sort by. Example: name
     * @queryParam sort_direction string The direction to sort. Example: asc
     */
    public function index()
    {
        $query = request()->query();

        $teachers = Teacher::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return TeacherResource::collection($teachers);
    }

    /**
     * Get teacher details
     * 
     * @urlParam teacher required The ID of the teacher. Example: 1
     */
    public function show(Teacher $teacher)
    {
        $teacher->load([
            'user',
            'subjects'
        ]);

        return $this->success(new TeacherResource($teacher), "Teacher retrieved successfully");
    }

    /**
     * Create a new teacher
     * 
     * @bodyParam name string required The name of the teacher. Example: teacher
     * @bodyParam email string required The email of the teacher. Example: teacher@mail.com
     * @bodyParam password string required The password of the teacher. Example: teacher
     * @bodyParam phone string required The phone of the teacher. Example: 081234567890
     * @bodyParam nip string required The nip of the teacher. Example: 123456789
     * @bodyParam profile_image file required The profile image of the teacher.
     * 
     * @subgroup Management
     */
    public function store(TeacherStoreRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'role_id' => 3
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'name' => $validated['name'],
                'date_of_birth' => $validated['date_of_birth'],
            ]);

            if (isset($validated['profile_image'])) {
                $image = $validated['profile_image'];
                $directory = 'teachers/images';
                $image->storeAs('public/' . $directory, $image->hashName(), 'local');
                $image_url = url('/storage/' . $directory . $image->hashName());

                $user->update(['profile_image' => $image_url]);
            }
        });

        $teacher = Teacher::latest()->first();

        return $this->success(new TeacherResource($teacher), "Teacher created succesfully" ,201);
    }

    /**
     * Update teacher
     * 
     * @urlParam teacher required The ID of the teacher. Example: 1
     * 
     * @bodyParam name string The name of the teacher. Example: teacher
     * @bodyParam email string The email of the teacher. Example: teacher@mail.com
     * @bodyParam phone string The phone of the teacher. Example: 081234567890
     * @bodyParam nip string The nip of the teacher. Example: 123456789
     * @bodyParam date_of_birth date The date of birth of the teacher. Example: 2000-01-01
     * @bodyParam profile_image file The profile image of the teacher.
     * 
     * @subgroup Management
     */
    public function update(TeacherUpdateRequest $request, Teacher $teacher)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $teacher) {
            $teacher->user->update([
                'email' => $validated['email'] ?? $teacher->user->email,
                'phone' => $validated['phone'] ?? $teacher->user->phone,
            ]);

            $teacher->update([
                'name' => $validated['name'] ?? $teacher->name,
                'nip' => $validated['nip'] ?? $teacher->nip,
                'date_of_birth' => $validated['date_of_birth'] ?? $teacher->date_of_birth,
            ]);

            if (isset($validated['profile_image'])) {
                $image = $validated['profile_image'];
                $directory = 'teachers/images';
                $image->storeAs('public/' . $directory, $image->hashName(), 'local');
                $image_url = url('/storage/' . $directory . $image->hashName());

                Storage::disk('local')->delete('public/' . $directory . basename($teacher->user->profile_image));

                $teacher->user->update(['profile_image' => $image_url]);
            }
        });

        return $this->success(new TeacherResource($teacher->refresh()), "Teacher updated successfully");
    }

    /**
     * Delete teacher
     * 
     * @urlParam teacher required The ID of the teacher. Example: 1
     * 
     * @subgroup Management
     */
    public function destroy(Teacher $teacher)
    {
        Gate::authorize('management');

        $teacher->delete();

        return $this->success(null, "Teacher deleted successfully");
    }
}
