<?php

namespace App\Http\Requests\Schools;

use Illuminate\Foundation\Http\FormRequest;

class GradesITeachRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools'],
            'user_id' => ['required', 'exists:users'],
            'grade' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
