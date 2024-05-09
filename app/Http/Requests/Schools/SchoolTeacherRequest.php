<?php

namespace App\Http\Requests\Schools;

use Illuminate\Foundation\Http\FormRequest;

class SchoolTeacherRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools'],
            'teacher_id' => ['required', 'exists:teachers'],
            'active' => ['boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
