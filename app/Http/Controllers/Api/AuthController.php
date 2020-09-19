<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\MeResource;
use App\Http\Resources\Auth\RegisterResource;
use App\Http\Traits\ResponseTrait;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return $this->successResponse(MeResource::make($user), 'User created successfully', 200);
    }

    public function login(LoginRequest $request)
    {
        if (!$token = Auth::guard('api')->attempt($request->validated())) {
            return $this->errorResponse('Wrong email or password', 401);
        }

        return $this->successResponse(['token' => $token],'Login successfully', 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request)
    {
        return $this->successResponse(MeResource::make(auth()->user()), 'Auth user', 200);
    }

}
