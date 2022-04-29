<?php

namespace App\Jav\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'model_type',
    ];

    protected $casts = [
        'model_id' => 'integer',
        'model_type' => 'string',
    ];
}
