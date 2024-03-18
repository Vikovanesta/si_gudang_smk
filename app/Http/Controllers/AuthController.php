<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\StudentRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\Student;
use App\Models\StudentRegistration;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
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
        $user = auth()->user();

        if ($user && ($user->isLaboran() || $user->isAdmin())) {
            $newUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'role_id' => 2
            ]);

            Student::create([
                'user_id' => $newUser->id,
                'nisn' => $validated['nisn'],
                'year_in' => $validated['year_in'],
                'date_of_birth' => $validated['date_of_birth']
            ]);

            $validated['user_id'] = $newUser->id;
        }
        else {
            $studentRegistration = StudentRegistration::create($validated);
    
            return $this->success(
                $studentRegistration, 
                'Registration has been sent. Please wait for confirmation', 
                201
            );
        }

    }

    public function verifyRegistration(Request $request, $id)
    {
        Gate::authorize('verify-registration');

        $request = $request->validate([
            'verify' => 'required|boolean'
        ]);

        $studentRegistration = StudentRegistration::findOrFail($id);

        if($request['verify']) {
            $newUser = User::create([
                'name' => $studentRegistration->name,
                'email' => $studentRegistration->email,
                'password' => Hash::make('password'),
                'phone' => $studentRegistration->phone,
                'role_id' => 2
            ]);

            Student::create([
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
        ], 201);
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
