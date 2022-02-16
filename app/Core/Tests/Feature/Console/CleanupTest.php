<?php

namespace App\Core\Tests\Feature\Console;

use App\Flickr\Models\FlickrProcess;
use Tests\TestCase;

class CleanupTest extends TestCase
{
    public function testCleanUp()
    {
        $process = FlickrProcess::factory()->create();

        $this->assertDatabaseHas('flickr_processes', [
            'step' => $process->step,
        ]);

        $process->completed();
        $this->artisan('core:cleanup');

        $this->assertDatabaseMissing('flickr_processes', [
            'id' => $process->id,
            'step' => $process->step,
        ]);
    }
}
