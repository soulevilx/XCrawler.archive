<?php

namespace App\Jav\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostWordPressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'confirm' => 'nullable|bool',
        ];
    }
}
