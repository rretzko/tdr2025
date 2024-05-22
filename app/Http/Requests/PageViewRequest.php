<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageViewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'header' => ['required'],
            'user_id' => ['required', 'exists:users'],
            'view_count' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
