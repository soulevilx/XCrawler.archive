<?php

namespace App\Flickr\Console\Commands;

use App\Core\Models\State;
use App\Flickr\Models\FlickrContact;
use App\Flickr\Models\FlickrProcess;
use Illuminate\Console\Command;
use App\Flickr\Models\FlickrPhoto as FlickrPhotoModel;

class Migration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate from previous version';

    public function handle()
    {
        $this->contacts();
        $this->photos();
    }

    public function contacts()
    {
        $this->output->title('Migrate Contacts');

        $data = $this->loadData('contacts');
        $this->output->progressStart(count($data));

        foreach ($data as $contact) {
            // Replace our state code
            $contact['state_code'] = State::STATE_INIT;
            $contact = FlickrContact::updateOrCreate([
                'nsid' => $contact['nsid'],
            ], $contact);
            $contact->process()->create([
                'step' => FlickrProcess::STEP_PEOPLE_INFO,
                'state_code' => State::STATE_INIT,
            ]);
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    public function photos()
    {
        $this->output->title('Migrate Photos');

        $data = $this->loadData('photos');
        $this->output->progressStart(count($data));

        foreach ($data as $photo) {
            unset($photo['state_code']);
            FlickrPhotoModel::updateOrCreate([
                'id' => $photo['id'],
            ], $photo);
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }


    private function loadData(string $fileName): array
    {
        $filePath = __DIR__ . '/../../Database/' . $fileName . '.json';
        if (!file_exists($filePath)) {
            return [];
        }

        $data = file_get_contents($filePath);
        return json_decode($data, true);
    }
}
