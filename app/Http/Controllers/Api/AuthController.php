<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\MeResource;
use App\Models\Invite;
use App\Models\Team;
use App\Models\TeamUserInvite;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        if ($request->has('token')) {
            $invite = Invite::where('token', $request->token)->first();
            DB::beginTransaction();
            try {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'username' => $request->username,
                    'email' => $invite->email,
                    'password' => bcrypt($request->password)
                ]);

                if ($invite->type == Invite::TYPE_TEAM) {
                    $team = Team::find($invite->type_id)->first();
                    $team->users()->attach($user->id);

                    TeamUserInvite::where([
                        'email' => $invite->email,
                        'team_id' => $team->id
                    ])->update(['status' => TeamUserInvite::STATUS_COMPLETED, 'user_id' => $user->id]);
                }
            } catch (\Exception $exception) {
                DB::rollBack();
                report($exception);
                return $this->errorResponse('Somethings wrong', 500);
            }
            DB::commit();
            return $this->successResponse(MeResource::make($user), 'Success register');

        }
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
    public function me(Request $request): JsonResponse
    {
        auth()->user()->load('teams', 'boards');
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
            report($exception);
            return $this->errorResponse('Password could not change', 500);
        }
        return $this->successResponse(null, 'Password change', 200);
    }

}
