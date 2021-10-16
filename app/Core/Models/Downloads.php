<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Downloads extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'model_type',
        'count'
    ];
}
