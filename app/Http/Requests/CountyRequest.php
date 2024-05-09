<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'geostate_id' => ['required', 'exists:geostates'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
