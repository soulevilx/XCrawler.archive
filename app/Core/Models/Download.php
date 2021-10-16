<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'model_type',
        'count',
    ];

    protected $casts = [
        'model_id' => 'integer',
        'model_type' => 'string',
        'count' => 'integer',
    ];
}
