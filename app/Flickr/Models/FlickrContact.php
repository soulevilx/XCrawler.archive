<?php

namespace App\Flickr\Models;

use App\Core\Models\Traits\HasFactory;
use App\Core\Models\Traits\HasStates;
use App\Flickr\Models\Traits\HasProcesses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property string $nsid
 * @property boolean $ispro
 * @property string $pro_badge
 * @property string $username
 * @property string $realname
 * @property string $description
 * @property array $photos
 * @property int $photos_count
 * @property-read  FlickrAlbum[]|Collection $albums
 * @package App\Models
 */
class FlickrContact extends Model
{
    use HasFactory;
    use HasStates;
    use SoftDeletes;
    use HasProcesses;

    protected $table = 'flickr_contacts';

    protected $fillable = [
        'nsid',
        'ispro',
        'pro_badge',
        'expire',
        'can_buy_pro',
        'iconserver',
        'iconfarm',
        'ignored',
        'path_alias',
        'has_stats',
        'gender',
        'contact',
        'friend',
        'family',
        'revcontact',
        'revfriend',
        'revfamily',
        'rev_ignored',
        'username',
        'realname',
        'mbox_sha1sum',
        'location',
        'timezone',
        'description',
        'photosurl',
        'profileurl',
        'mobileurl',
        'photos',
        'photos_count',
    ];

    protected $casts = [
        'nsid' => 'string',
        'ispro' => 'boolean',
        'pro_badge' => 'string',
        'expire' => 'int',
        'can_buy_pro' => 'int',
        'iconserver' => 'string',
        'iconfarm' => 'string',
        'ignored' => 'int',
        'path_alias' => 'string',
        'has_stats' => 'int',
        'gender' => 'string',
        'contact' => 'int',
        'friend' => 'int',
        'family' => 'int',
        'revcontact' => 'int',
        'revfriend' => 'int',
        'revfamily' => 'int',
        'rev_ignored' => 'int',
        'username' => 'string',
        'realname' => 'string',
        'mbox_sha1sum' => 'string',
        'location' => 'string',
        'timezone' => 'array',
        'description' => 'string',
        'photosurl' => 'string',
        'profileurl' => 'string',
        'mobileurl' => 'string',
        'photos' => 'array',
        'photos_count' => 'integer',
    ];

    public static function findByNsid(string $nsid)
    {
        return self::withTrashed()->where('nsid', $nsid)->first();
    }

    public function albums(): HasMany
    {
        return $this->hasMany(FlickrAlbum::class, 'owner', 'nsid');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(FlickrPhoto::class, 'owner', 'nsid');
    }
}
