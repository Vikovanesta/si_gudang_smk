<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
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
