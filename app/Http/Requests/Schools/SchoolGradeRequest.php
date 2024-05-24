<?php

namespace App\Http\Requests\Schools;

use Illuminate\Foundation\Http\FormRequest;

class SchoolGradeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools'],
            'grade' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
