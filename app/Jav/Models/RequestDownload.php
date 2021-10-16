<?php

namespace App\Jav\Models;

use App\Core\Models\BaseModel;
use App\Core\Models\Traits\HasStates;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestDownload extends BaseModel
{
    use HasFactory;
    use HasStates;

    protected $fillable = [
        'model_id',
        'model_type',
    ];

    protected $casts = [
        'model_id' => 'integer',
        'model_type' => 'string',
    ];
}
