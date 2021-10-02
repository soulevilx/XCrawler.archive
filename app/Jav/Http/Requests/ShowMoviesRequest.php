<?php

namespace App\Jav\Http\Requests;

class ShowMoviesRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules(): array
    {
        return [
            'keyword' => 'nullable|string',
        ];
    }
}
