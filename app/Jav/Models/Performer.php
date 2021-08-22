<?php

namespace App\Jav\Models;

use App\Core\Models\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 * @property array  $alias
 * @property string $birthday
 * @property string $blood_type
 * @property string $city
 * @property int    $height
 * @property int    $breast
 * @property int    $waist
 * @property int    $hips
 * @property string $cover
 * @property int    $favorite
 */
class Performer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'performers';

    protected $fillable = [
        'name',
        'alias',
        'birthday',
        'blood_type',
        'city',
        'height',
        'breast',
        'waist',
        'hips',
        'cover',
        'favorite',
    ];

    protected $casts = [
        'name' => 'string',
        'alias' => 'array',
        'birthday' => 'datetime:Y-m-d',
        'blood_type' => 'string',
        'city' => 'string',
        'height' => 'integer',
        'breast' => 'integer',
        'waist' => 'integer',
        'hips' => 'integer',
        'cover' => 'string',
        'favorite' => 'integer',
    ];
}
