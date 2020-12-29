<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\MeResource;
use App\Http\Resources\Auth\RegisterResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
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

    public function login(LoginRequest $request): JsonResponse
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

    /**
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return $this->errorResponse('Old password not same password', 400);
        }

        try {
            auth()->user()->update(['password' => Hash::make($request->password)]);
        } catch (\Exception $exception) {
            return $this->errorResponse('Password could not change', 500);
        }
        return $this->successResponse(null, 'Password change', 200);
    }

}
