<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeostateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'country_abbr' => ['required'],
            'name' => ['required'],
            'abbr' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
