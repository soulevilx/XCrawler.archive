<?php

namespace App\Jav\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WordPressPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'model_id' => $this->resource->model_id,
            'model_type' => $this->resource->model_type,
            'title' => $this->resource->title,
            'state_code' => $this->resource->state_code,
        ];
    }
}
