<?php

namespace App\Http\Requests\Card\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCardUserRequest extends FormRequest
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
        return [
            'users' => ['required', 'array'],
            'users.*' => [
                'exists:users,id',
            ],
        ];
    }
}
