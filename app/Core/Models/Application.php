<?php

namespace App\Core\Models;

use App\Core\Models\Traits\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'settings',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'settings' => 'array',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    public function scopeByName($query, string $name)
    {
        return $query->where(['name' => $name]);
    }
}
