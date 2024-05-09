<?php

namespace App\Http\Requests\Schools;

use Illuminate\Foundation\Http\FormRequest;

class SchoolRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'postal_code' => ['required'],
            'city' => ['required'],
            'county_id' => ['required', 'exists:counties'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
