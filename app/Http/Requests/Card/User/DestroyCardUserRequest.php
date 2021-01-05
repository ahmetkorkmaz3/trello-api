<?php

namespace App\Http\Requests\Card\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DestroyCardUserRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        // TODO: Check exists user in assigned card
        return [
            'users' => ['required', 'array'],
            'users.*' => ['exists:card_users,user_id']
        ];
    }
}
