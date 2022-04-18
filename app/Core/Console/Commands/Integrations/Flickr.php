<?php

namespace App\Core\Console\Commands\Integrations;

use App\Core\Models\Integration;
use App\Flickr\Services\FlickrService;
use Illuminate\Console\Command;

class Flickr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integrate:flickr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching Onejav';

    public function handle(FlickrService $service)
    {
        $this->output->title('Integration with Flickr');
        $url = $service->getAuthUrl();
        $this->output->text($url->getAbsoluteUri());

        $code = $this->output->ask('Enter code');
        $accessToken = $service->retrieveAccessToken($code);

        Integration::create([
                'service' => FlickrService::SERVICE_NAME,
                'token_secret' => $accessToken->getAccessTokenSecret(),
                'token' => $accessToken->getAccessToken(),
                'data' => json_encode($accessToken)
        ]);

        $this->table(
            [
                'service',
                'token_secret',
                'token',
            ],
            [
                [
                    FlickrService::SERVICE_NAME,
                    $accessToken->getAccessTokenSecret(),
                    $accessToken->getAccessToken(),
                ],
            ]
        );
        $this->output->success('Flick integrated');
    }
}
