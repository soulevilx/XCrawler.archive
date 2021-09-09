<?php

namespace App\Flickr\Console\Commands;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Models\FlickrContact;
use Illuminate\Console\Command;

class FlickrPeople extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:people';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get people data';

    public function handle()
    {
        $contact = FlickrContact::byState(State::STATE_INIT)->first();

        if (!$contact) {
            return;
        }

        $contact->setState(State::STATE_PROCESSING);
        FLickrPeopleInfo::dispatch($contact)->onQueue('api');
    }
}
