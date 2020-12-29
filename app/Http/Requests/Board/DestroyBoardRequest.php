<?php

namespace App\Http\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DestroyBoardRequest extends FormRequest
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
        return [];
    }
}
