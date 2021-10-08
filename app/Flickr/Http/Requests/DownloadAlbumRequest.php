<?php

namespace App\Flickr\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadAlbumRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => 'nullable|string',
        ];
    }
}
