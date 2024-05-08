<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViewPageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'controller' => ['required'],
            'method' => ['required'],
            'page_name' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
