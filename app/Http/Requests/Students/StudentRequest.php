<?php

namespace App\Http\Requests\Students;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'class_of' => ['required', 'integer'],
            'user_id' => ['required', 'exists:users'],
            'height' => ['required', 'integer'],
            'birthday' => ['required'],
            'shirt_size' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
