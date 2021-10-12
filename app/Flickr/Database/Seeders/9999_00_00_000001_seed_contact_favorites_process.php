<?php

use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class SeedContactFavoritesProcess extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        if (app()->environment('testing')) {
            return;
        }

        $now = Carbon::now();
        foreach (FlickrContact::cursor() as $contact) {
            $contact->process()->create([
                'step' => FlickrProcess::STEP_PEOPLE_FAVORITE_PHOTOS,
                'state_code' => State::STATE_INIT,
                'created_at' => $now
            ]);
        }
    }
}
