<?php

namespace App\Flickr\Console\Commands;

use App\Flickr\Console\Commands\Traits\HasProcesses;
use App\Flickr\Jobs\FlickrFavorites;
use App\Flickr\Jobs\FlickrPeopleInfo;
use App\Flickr\Jobs\FlickrPeoplePhotos as FlickrPeoplePhotosJob;
use App\Flickr\Models\FlickrProcess;
use Illuminate\Console\Command;

/**
 * Step 1
 * - Getting people information
 * - Getting people' photos
 */
class FlickrPeople extends Command
{
    use HasProcesses;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:people {task}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get people data. Tasks: info || photos';

    public function handle()
    {
        $this->output->title('Flickr process people. Getting ' . ucfirst($this->argument('task')));
        switch ($this->argument('task')) {
            case 'info':
                $this->peopleInfo();
                break;
            case 'photos':
                $this->peoplePhotos();
                break;
            case 'favorites':
                $this->peopleFavorites();
                break;
        }
    }

    protected function peopleInfo()
    {
        /**
         * Whenever contact is created it'll create process STEP_PEOPLE_INFO
         * This process will fetch detail people information
         */
        if ($process = $this->getProcessItem(FlickrProcess::STEP_PEOPLE_INFO)) {
            FlickrPeopleInfo::dispatch($process)->onQueue('api');
        }
    }

    protected function peoplePhotos()
    {
        /**
         * After STEP_PEOPLE_INFO completed will create STEP_PEOPLE_PHOTOS
         * This process will fetch all photos of an contact
         */

        if ($process = $this->getProcessItem(FlickrProcess::STEP_PEOPLE_PHOTOS)) {
            FlickrPeoplePhotosJob::dispatch($process)->onQueue('api');
        }
    }

    protected function peopleFavorites()
    {
        if ($process = $this->getProcessItem(FlickrProcess::STEP_PEOPLE_FAVORITE_PHOTOS)) {
            FlickrFavorites::dispatch($process)->onQueue('api');
        }
    }
}
