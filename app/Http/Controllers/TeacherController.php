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

class TeacherController extends Controller
{
    use HttpResponses;

    public function index()
    {
        $query = request()->query();

        $teachers = Teacher::filterByQuery($query)->paginate($query['page_size'] ?? 15);

        return $this->successResponse($teachers);
    }

    public function show(Teacher $teacher)
    {
        $teacher->load([
            'user',
            'subjects'
        ]);

        return $this->success(new TeacherResource($teacher), "Teacher retrieved successfully");
    }

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

    public function destroy(Teacher $teacher)
    {
        Gate::authorize('management');

        $teacher->delete();

        return $this->success(null, "Teacher deleted successfully");
    }
}
