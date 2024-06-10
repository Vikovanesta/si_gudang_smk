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

/**
 * @group Student
 * 
 * APIs for managing students
 * 
 * @authenticated
 */
class StudentController extends Controller
{
    use HttpResponses;

    /**
     * Get students
     * 
     * Get a list of students
     * 
     * @queryParam class_id integer The id of the class. Example: 1
     * @queryParam name string The name of the student. Example: student
     * @queryParam nisn string The nisn of the student. Example: 123456789
     * @queryParam min_date_of_birth date The minimum date of birth of the student. Example: 2000-01-01
     * @queryParam max_date_of_birth date The maximum date of birth of the student. Example: 2000-01-01
     * @queryParam min_year_in date The minimum year in of the student. Example: 2020
     * @queryParam max_year_in date The maximum year in of the student. Example: 2023
     * @queryParam page integer The page number. Example: 1
     * @queryParam page_size integer The number of students to display per page. Example: 15
     * @queryParam sort_by string The column to sort by. Example: name
     * @queryParam sort_direction string The direction to sort. Example: asc
     */
    public function index()
    {
        
        $query = request()->query();
        
        $students = Student::filterByQuery($query)->paginate($query['page_size'] ?? 15);
        
        return StudentResource::collection($students);
    }

    /**
     * Get student details
     * 
     * @urlParam student required The ID of the student. Example: 1
     */
    public function show(Student $student)
    {
        $student->load([
            'user',
            'class'
        ]);

        return $this->success(new StudentResource($student), "Student retrieved successfully");
    }

    /**
     * Add student
     * 
     * Add a new student
     * 
     * @bodyParam class_id integer required The id of the class. Example: 1
     * @bodyParam name string required The name of the student. Example: student
     * @bodyParam nisn string required The nisn of the student. Example: 123456789
     * @bodyParam year_in date required The year in of the student. Example: 2020
     * @bodyParam date_of_birth date required The date of birth of the student. Example: 2000-01-01
     * @bodyParam email string required The email of the student. Example: student@mail.com
     * @bodyParam password string required The password of the student. Example: password
     * @bodyParam phone string required The phone of the student. Example: 081234567890
     * @bodyParam profile_image file The profile image of the student.
     * 
     * @subgroup Management
     */
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

    /**
     * Update student
     * 
     * @urlParam student required The ID of the student. Example: 1
     * 
     * @bodyParam class_id integer The id of the class. Example: 1
     * @bodyParam name string The name of the student. Example: student
     * @bodyParam email string The email of the student. Example: student@mail.com
     * @bodyParam phone string The phone of the student. Example: 081234567890
     * @bodyParam nisn string The nisn of the student. Example: 123456789
     * @bodyParam year_in date The year in of the student. Example: 2020
     * @bodyParam date_of_birth date The date of birth of the student. Example: 2000-01-01
     * @bodyParam profile_image file The profile image of the student.
     * 
     * @subgroup Management
     */
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

    /**
     * Delete student
     * 
     * @urlParam student required The ID of the student. Example: 1
     * 
     * @subgroup Management
     */
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
