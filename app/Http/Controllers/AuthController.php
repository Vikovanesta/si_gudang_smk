<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\EmployeeRegistrationRequest;
use App\Http\Requests\StudentRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\Student;
use App\Models\StudentRegistration;
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

    public function indexRegistration(Request $request)
    {

    }

    public function registerStudent(StudentRegistrationRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        // dd($user);

        // Check if student already registered
        $studentByNisn = Student::where('nisn', $validated['nisn'])->first();
        $studentByEmail = User::where('email', $validated['email'])->first();
        if ($studentByNisn || $studentByEmail) {
            return $this->error(
                null,
                'Student already registered',
                400
            );
        }

        if ($user && ($user->isLaboran() || $user->isAdmin())) {
            $newUser = User::create([
                'email' => $validated['email'],
                'password' => $validated['password'],
                'phone' => $validated['phone'],
                'role_id' => 2
            ]);
            
            Student::create([
                'user_id' => $newUser->id,
                'class_id' => $validated['class_id'],
                'name' => $validated['name'],
                'nisn' => $validated['nisn'],
                'year_in' => $validated['year_in'],
                'date_of_birth' => $validated['date_of_birth']
            ]);

            return $this->success(
                new UserResource($newUser), 
                'Student has been registered', 
                201
            );
        }
        else if (!$user) {
            $studentRegistration = StudentRegistration::create($validated);
    
            return $this->success(
                $studentRegistration, 
                'Registration has been sent. Please wait for confirmation', 
                201
            );
        }
        else {
            return $this->error(
                null,
                'Unauthorized',
                401
            );
        }

    }

    public function registerEmployee(EmployeeRegistrationRequest $request) 
    {
        $validated = $request->validated();
    }

    public function verifyRegistration(Request $request, $id)
    {
        Gate::authorize('management');

        $request = $request->validate([
            'verify' => 'required|boolean'
        ]);

        $studentRegistration = StudentRegistration::findOrFail($id);

        if($request['verify']) {
            $newUser = User::create([
                'email' => $studentRegistration->email,
                'password' => $studentRegistration->password,
                'phone' => $studentRegistration->phone,
                'role_id' => 2
            ]);
            
            Student::create([
                'name' => $studentRegistration->name,
                'user_id' => $newUser->id,
                'nisn' => $studentRegistration->nisn,
                'year_in' => $studentRegistration->year_in,
                'date_of_birth' => $studentRegistration->date_of_birth
            ]);

            $studentRegistration->update(['is_verified' => true]);
        }
        else {
            $studentRegistration->update(['is_verified' => false]);
        }

        return $this->success(
            $studentRegistration, 
            'Registration has been verified', 
            200
        );
    }

    /**
     * Login
     * 
     * Login to the application
     * 
     * @bodyParam name string required The name of the user. Example: merchant
     * @bodyParam email string required The email of the user. Example: merchant@mail.com
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
}
