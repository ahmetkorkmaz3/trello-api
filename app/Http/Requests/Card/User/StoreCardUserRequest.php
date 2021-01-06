<?php

namespace App\Http\Requests\Card\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
                'exists:board_users,user_id,board_id,' . $this->route('card')->column->board->id,
            ],
        ];
    }
}
