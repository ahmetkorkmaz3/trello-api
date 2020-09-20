<?php

namespace App\Http\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['required', 'integer', 'exists:users,id']
        ];
    }
}
