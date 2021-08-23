<?php

namespace App\Core\Models;

use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WordPressPost extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasStates;

    protected $table = 'wordpress_posts';

    protected $fillable = [
        'model_id',
        'model_type',
        'title',
        'state_code',
    ];

    protected $casts = [
        'model_id' => 'int',
        'model_type' => 'string',
        'title' => 'string',
        'state_code' => 'string',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
