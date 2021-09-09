<?php

namespace App\Flickr\Models;

use App\Core\Models\Traits\HasStates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlickrContactProcess extends Model
{
    use HasFactory;
    use HasStates;

    protected $table = 'flickr_contact_processes';

    public const STEP_PEOPLE_INFO = 'people_info';

    protected $fillable = [
        'step',
        'state_code',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
