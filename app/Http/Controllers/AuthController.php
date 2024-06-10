<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\EmployeeRegistrationRequest;
use App\Http\Requests\StudentRegistrationRequest;
use App\Http\Resources\StudentRegistrationResource;
use App\Http\Resources\UserResource;
use App\Models\Laboran;
use App\Models\Student;
use App\Models\StudentRegistration;
use App\Models\Teacher;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

/**
 * @group Auth
 *
 * APIs for authentication
 */
class AuthController extends Controller
{
    use HttpResponses;

    /**
     * Login
     * 
     * Login to the application
     * 
     * @bodyParam name string required The name of the user. Example: student
     * @bodyParam email string required The email of the user. Example: student@mail.com
     * @bodyParam password string required The password of the user. Example: password
     * */
    public function login(AuthLoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'User not found',
                    'errors' => [
                        'message' => 'Incorrect email or password',
                    ]
                ], 401));
        }

        $token = $user->createToken('access-token')->plainTextToken;

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token
        ], 'Login Success', 200);
    }

    /**
     * List Student Registration
     * 
     * Get list of student registration
     * 
     * @subgroup Management
     * @authenticated
     * */
    public function indexRegistration(Request $request)
    {
        Gate::authorize('management');

        $query = $request->query();

        $studentRegistrations = StudentRegistration::paginate($query['page_size'] ?? 15);

        return StudentRegistrationResource::collection($studentRegistrations);
    }

    /**
     * Register student
     * 
     * Self registration for student
     * 
     * @bodyParam class_id integer required The class id of the student. Example: 1
     * @bodyParam name string required The name of the student. Example: Abdul al-karim
     * @bodyParam email string required The email of the student. Example: abdull@mail.com
     * @bodyParam password string required The password of the student. Example: password
     * @bodyParam phone string required The phone number of the student. Example: 081234567890
     * @bodyParam nisn string required The NISN of the student. Example: 1234567890
     * @bodyParam year_in integer required The year in of the student. Example: 2021
     * @bodyParam date_of_birth date required The date of birth of the student. Example: 2000-01-01
     * */
    public function registerStudent(StudentRegistrationRequest $request)
    {
        $validated = $request->validated();

        if ($this->isStudentRegistered($validated['nisn'], $validated['email'])) {
            return $this->error(
                null,
                'Student already registered',
                400
            );
        }

        $studentRegistration = StudentRegistration::create($validated);

        return $this->success(
            new StudentRegistrationResource($studentRegistration), 
            'Registration has been sent. Please wait for confirmation', 
            201
        );
    }

    /**
     * Verify student registration
     * 
     * Verify student registration
     * 
     * @urlParam id integer required The id of the student registration. Example: 1
     * @bodyParam verify boolean required The verification status. Example: true
     * 
     * @subgroup Management
     * @authenticated
     * */
    public function verifyRegistration(Request $request, $id)
    {
        Gate::authorize('management');

        $request = $request->validate([
            'verify' => 'required|boolean'
        ]);

        $studentRegistration = StudentRegistration::findOrFail($id);

        if ($this->isStudentRegistered($studentRegistration->nisn, $studentRegistration->email)) {
            return $this->error(
                null,
                'Student already registered',
                400
            );
        }

        if($request['verify']) {
            $newUser = User::create([
                'email' => $studentRegistration->email,
                'password' => $studentRegistration->password,
                'phone' => $studentRegistration->phone,
                'role_id' => 2
            ]);
            
            $student = Student::create([
                'class_id' => $studentRegistration->class_id,
                'name' => $studentRegistration->name,
                'user_id' => $newUser->id,
                'nisn' => $studentRegistration->nisn,
                'year_in' => $studentRegistration->year_in,
                'date_of_birth' => $studentRegistration->date_of_birth
            ]);

            $studentRegistration->update([
                'is_verified' => true,
                'verifier_id' => Auth::id(),
                'verified_at' => now()
            ]);

            return $this->success(
                new UserResource($newUser), 
                'Student has been succesfully registered', 
                201
            );
        }
        else {
            $studentRegistration->update(['is_verified' => false]);

            $studentRegistration->update([
                'is_verified' => false,
                'verifier_id' => Auth::id(),
                'verified_at' => now()
            ]);

            return $this->success(
                new StudentRegistrationResource($studentRegistration), 
                'Registration has been rejected', 
                200
            );
        }
    }

    /**
     * Get my data
     * 
     * Get current logged in user data
     * 
     * @authenticated
     * */
    public function me()
    {
        return $this->success(new UserResource(auth()->user()), 'User data retrieved', 200);
    }

    /**
     * Logout
     * 
     * Logout from the application
     * 
     * @authenticated
     * 
     * @response 200 {
     * "message": "Logged out"
     * }
     * */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return $this->success(null ,'Logged out', 200);
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
