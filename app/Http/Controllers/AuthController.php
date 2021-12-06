<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelpers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    use ResponseHelpers;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'login',
            'register'
        ]]);
    }

    /**
     * @param LoginRequest $loginRequest
     * @return ResponseHelpers
     */
    public function login(LoginRequest $loginRequest)
    {

        $token = auth()->attempt($loginRequest->validated());
        if ($token == true) {

            return $this->createNewToken($token);

        }
        return $this->response([
            'error' => 'Unauthorized'
        ], 401);

    }

    /**
     * @param RegisterRequest $registerRequest
     * @return ResponseHelpers
     */
    public function register(RegisterRequest $registerRequest)
    {
        $user = User::create(array_merge(
            $registerRequest->validated(),
            ['password' => Hash::make($registerRequest->password)]
        ));

        return $this->response([
            'message' => 'User successfully registered',
            'user' => $user
        ]);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->response(['message' => 'User successfully signed out']);
    }

    /**
     * @return ResponseHelpers
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * @return ResponseHelpers
     */
    public function userProfile()
    {
        return $this->response(auth()->user());
    }


    /**
     * @param $token
     * @return ResponseHelpers
     */
    protected function createNewToken($token)
    {
        return $this->response([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

}
