<?php

namespace App\Jav\Models;

use App\Core\Models\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $name
 * @property-read Movie|[] $movies
 */
class Genre extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'genres';

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'string',
    ];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_genres');
    }
}
