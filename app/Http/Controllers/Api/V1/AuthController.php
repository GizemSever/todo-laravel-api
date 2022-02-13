<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = new User($request->validated());
        $user->password = bcrypt($request->password);
        $user->save();

        $token = $this->createToken($user);
        return $this->successAuthResponse($token, $user);
    }

    /**
     * Create a sanctum access token from User model
     *
     * @param User $user
     * @return string
     */
    private function createToken(User $user)
    {
        return $user->createToken('todoApp')->plainTextToken;
    }

    /**
     * Send success response with User resource and token
     *
     * @param string $token
     * @param User $user
     * @return mixed
     */
    private function successAuthResponse(string $token, User $user)
    {
        $response = [
            'token' => $token,
            'user' => new UserResource($user)
        ];

        return response()->success($response, Response::HTTP_OK);
    }

    public function login(LoginRequest $loginRequest)
    {
        if (Auth::attempt($loginRequest->validated())) {
            $user = Auth::user();

            $token = $this->createToken($user);
            return $this->successAuthResponse($token, $user);
        }
        return response()->error(null, Response::HTTP_UNAUTHORIZED, __('auth.unauthorized_login'));
    }

    public function user()
    {
        $user = Auth::user();
        return response()->success(new UserResource($user), Response::HTTP_OK);
    }
}
