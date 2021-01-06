<?php

namespace App\Http\Requests\Card\CheckList;

use App\Models\CardCheckList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCheckListRequest extends FormRequest
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
            'text' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in([CardCheckList::STATUS_INCOMPLETE, CardCheckList::STATUS_COMPLETE])],
        ];
    }
}
