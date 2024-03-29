<?php

namespace App\Jav\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowMoviesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'keyword' => 'nullable|string',
        ];
    }
}
