<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    use HttpResponses;

    public function index()
    {
        
        $query = request()->query();
        
        $students = Student::filterByQuery($query)->paginate($query['page_size'] ?? 15);
        
        return StudentResource::collection($students);
    }

    public function show(Student $student)
    {
        $student->load([
            'user',
            'class'
        ]);

        return $this->success(new StudentResource($student), "Student retrieved successfully");
    }

    public function store(StudentStoreRequest $request)
    {
        $validated = $request->validated();

        if ($this->isStudentRegistered($validated['nisn'], $validated['email'])) {
            return $this->error(
                null,
                'Student already registered',
                400
            );
        }

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'role_id' => 2
            ]);

            Student::create([
                'user_id' => $user->id,
                'class_id' => $validated['class_id'],
                'name' => $validated['name'],
                'nisn' => $validated['nisn'],
                'year_in' => $validated['year_in'],
                'date_of_birth' => $validated['date_of_birth'],
            ]);

            if (isset($validated['profile_image'])) {
                $profileImage = $validated['profile_image'];
                $directory = 'students/images';
                $profileImage->storeAs('public/' . $directory, $profileImage->hashName(), 'local');
                $profileImageUrl = url('/storage/' . $directory . $profileImage->hashName());

                $user->update(['profile_image' => $profileImageUrl]);
            }
        });

        $student = Student::latest()->first();

        return $this->success(new StudentResource($student), "Student created successfully", 201);
    }

    public function update(StudentUpdateRequest $request, Student $student)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $student) {
            $student->update([
                'class_id' => $validated['class_id'] ?? $student->class_id,
                'name' => $validated['name'] ?? $student->name,
                'nisn' => $validated['nisn'] ?? $student->nisn,
                'year_in' => $validated['year_in'] ?? $student->year_in,
                'date_of_birth' => $validated['date_of_birth'] ?? $student->date_of_birth,
            ]);

            $user = $student->user;
            $user->update([
                'email' => $validated['email'] ?? $user->email,
                'phone' => $validated['phone'] ?? $user->phone,
            ]); 

            if (isset($validated['profile_image'])) {
                $image = $validated['profile_image'];
                $directory = 'students/images';
                $image->storeAs('public/' . $directory, $image->hashName(), 'local');
                $profileImageUrl = url('/storage/' . $directory . $image->hashName());

                Storage::disk('local')->delete('public/' . $directory . basename($user->profile_image));

                $user->update(['profile_image' => $profileImageUrl]);
            }
        });

        return $this->success(new StudentResource($student->refresh()), "Student updated successfully");
    }

    public function destroy(Student $student)
    {
        Gate::authorize('management');

        $student->user->delete();

        return $this->success(null, "Student deleted successfully");
    }


    private function isStudentRegistered($nisn, $email)
    {
        $studentByNisn = Student::where('nisn', $nisn)->first();
        $studentByEmail = User::where('email', $email)->first();
        if ($studentByNisn || $studentByEmail) {
            return true;
        }
    }
}
