<?php

namespace App\Flickr\Tests\Feature\Console;

use App\Flickr\Jobs\FlickrContacts;
use App\Flickr\Tests\FlickrTestCase;
use Illuminate\Support\Facades\Queue;

class FlickrContactsTest extends FlickrTestCase
{
    public function testCommand()
    {
        Queue::fake();
        $this->artisan('flickr:contacts');
        Queue::assertPushed(FlickrContacts::class, function ($job) {
            return $job->queue === 'api';
        });
    }
}
