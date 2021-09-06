<?php

namespace App\Flickr\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlickrContact extends Model
{
    use HasFactory;

    protected $table='flickr_contacts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'nsid';

    protected $keyType = 'string';

    public $incrementing = false;

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
        'state_code',
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
        'state_code' => 'string',
    ];
}
