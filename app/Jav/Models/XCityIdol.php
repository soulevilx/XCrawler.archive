<?php

namespace App\Jav\Models;

use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use App\Jav\Models\Interfaces\MovieInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $url
 * @property string $name
 * @property string $cover
 * @property int    $favorite
 * @property string $birthday
 * @property string $blood_type
 * @property string $city
 * @property int    $height
 * @property int    $breast
 * @property int    $waist
 * @property int    $hips
 * @property string $state_code
 *
 * @method static Builder|XCityIdol byState (string $state)
 */
class XCityIdol extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasStates;

    public const SERVICE = 'xcity_idol';

    public const BASE_URL = 'https://xxx.xcity.jp';
    public const INDEX_URL = 'idol/';
    public const PER_PAGE = 30;

    public const STATE_INIT = 'XCIN';
    public const STATE_PENDING = 'XCPE';
    public const STATE_PROCESSING = 'XCPR';
    public const STATE_COMPLETED = 'XCCE';

    protected $table = 'xcity_idols';

    protected $fillable = [
        'url',
        'name',
        'cover',
        'favorite',
        'birthday',
        'blood_type',
        'city',
        'height',
        'breast',
        'waist',
        'hips',
        'skill',
        'other',
        'state_code',
    ];

    protected $casts = [
        'url' => 'string',
        'name' => 'string',
        'cover' => 'string',
        'favorite' => 'integer',
        'birthday' => 'datetime:Y-m-d',
        'blood_type' => 'string',
        'city' => 'string',
        'height' => 'string',
        'breast' => 'integer',
        'waist' => 'integer',
        'hips' => 'integer',
        'skill' => 'string',
        'other' => 'string',
        'state_code' => 'string',
    ];

    public function refetch(): MovieInterface
    {
        return $this;
    }
}
