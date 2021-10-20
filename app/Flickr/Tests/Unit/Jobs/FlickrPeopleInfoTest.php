<?php

namespace App\Flickr\Tests\Unit\Jobs;

use App\Core\Models\State;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use App\Flickr\Tests\FlickrTestCase;

class FlickrPeopleInfoTest extends FlickrTestCase
{
    public function testJob()
    {
        $contact = FlickrContact::factory()->create();
        $process = $contact->processStep(FlickrProcess::STEP_PEOPLE_INFO);
        FlickrPeopleInfo::dispatch($process);

        $contact->refresh();
        $process->refresh();
        $this->assertEquals(State::STATE_COMPLETED, $process->state_code);
        $this->assertEquals(4820, $contact->iconserver);
        $this->assertEquals('soulevilx', $contact->path_alias);
        $this->assertEquals('SoulEvilX', $contact->username);
    }

    public function testJobWithDeletedUser()
    {
        $contact = FlickrContact::factory()->create([
            'nsid' => 'deleted',
        ]);
        $process = $contact->processStep(FlickrProcess::STEP_PEOPLE_INFO);

        FlickrPeopleInfo::dispatch($process);

        $this->assertSoftDeleted($contact->refresh());
        $this->assertEquals(State::STATE_FAILED, $process->refresh()->state_code);
    }
}
