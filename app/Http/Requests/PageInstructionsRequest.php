<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageInstructionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'header' => ['required'],
            'instructions' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
