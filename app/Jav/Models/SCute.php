<?php

namespace App\Jav\Models;

use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use App\Jav\Models\Traits\HasMovie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SCute extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasStates;

    protected $table = 'scutes';

    protected $fillable = [
        'url',
        'cover',
        'images',
    ];

    protected $casts = [
        'url' => 'string',
        'cover' => 'string',
        'images' => 'array',
    ];
}
