<?php

namespace App\Http\Requests\BoardUser;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardUserRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return false;
    }
}
