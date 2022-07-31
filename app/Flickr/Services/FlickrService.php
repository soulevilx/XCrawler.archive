<?php

namespace App\Flickr\Services;

use App\Core\Models\Integration;
use App\Flickr\Events\FlickrRequested;
use App\Flickr\Events\FlickrRequestFailed;
use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Jobs\FlickrRequestDownloadAlbum;
use App\Flickr\Services\Flickr\Contacts;
use App\Flickr\Services\Flickr\Entities\Album;
use App\Flickr\Services\Flickr\Favorites;
use App\Flickr\Services\Flickr\People;
use App\Flickr\Services\Flickr\Photos;
use App\Flickr\Services\Flickr\PhotoSets;
use App\Flickr\Services\Flickr\Urls;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Client\CurlClient;
use OAuth\Common\Http\Uri\UriInterface;
use OAuth\Common\Storage\Memory;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth1\Token\StdOAuth1Token;
use OAuth\OAuth2\Token\TokenInterface;
use OAuth\ServiceFactory;

class FlickrService
{
    public const SERVICE_NAME = 'flickr';

    protected TokenStorageInterface $oauthTokenStorage;

    /**
     * @var TokenInterface
     */
    protected $oauthRequestToken;

    public function __construct(private ?string $apiKey = null, private ?string $secret = null)
    {
        $this->oauthTokenStorage = new Memory;
    }

    protected function getClient(string $callbackUrl = 'oob')
    {
        if ($integration = $this->getIntegration()) {
            $token = new StdOAuth1Token();
            $token->setAccessToken($integration->token);
            $token->setAccessTokenSecret($integration->token_secret);
            $this->oauthTokenStorage->storeAccessToken('Flickr', $token);
        }

        $credentials = new Credentials($this->apiKey, $this->secret, $callbackUrl);

        return app(ServiceFactory::class)
            ->setHttpClient(new CurlClient)
            ->createService('Flickr', $credentials, $this->oauthTokenStorage);
    }

    public function getIntegration()
    {
        return Integration::byService(self::SERVICE_NAME)->first();
    }

    public function retrieveAccessToken($verifier, $requestToken = null)
    {
        /**
         * @var \OAuth\OAuth1\Token\TokenInterface $token
         */
        $token = $this->oauthTokenStorage->retrieveAccessToken('Flickr');

        // If no request token is provided, try to get it from this object.
        if (is_null($requestToken) && $this->oauthRequestToken instanceof TokenInterface) {
            $requestToken = $this->oauthRequestToken->getAccessToken();
        }

        $secret = $token->getAccessTokenSecret();
        $accessToken = $this->getClient()->requestAccessToken($requestToken, $verifier, $secret);
        $this->oauthTokenStorage->storeAccessToken('Flickr', $accessToken);

        return $accessToken;
    }

    public function getAuthUrl($perm = 'read', $callbackUrl = 'oob'): UriInterface
    {
        $service = $this->getClient($callbackUrl);
        $this->oauthRequestToken = $service->requestRequestToken();

        return $service->getAuthorizationUri([
            'oauth_token' => $this->oauthRequestToken->getAccessToken(),
            'perms' => $perm,
        ]);
    }

    /**
     * @throws FlickrGeneralException
     */
    public function request(string $path, array $params): ?array
    {
        $params = array_filter($params);

        $jsonResponse = json_decode(
            $this->getClient()->requestJson($path, 'POST', $params),
            true
        );

        Event::dispatch(new FlickrRequested(
            'POST',
            $path,
            $params,
            $jsonResponse
        ));

        if (isset($jsonResponse['stat']) && $jsonResponse['stat'] !== 'fail') {
            return $this->cleanTextNodes($jsonResponse);
        }

        Event::dispatch(new FlickrRequestFailed($path, $params, $jsonResponse ?? []));

        $exception = new FlickrGeneralException(
            $jsonResponse['message'] ?? '',
            $jsonResponse['code'] ?? null
        );

        throw new $exception;
    }

    /**
     * Normalize text nodes in API results.
     *
     * @param mixed $arr The node to normalize.
     * @return mixed
     * @private
     */
    private function cleanTextNodes($arr)
    {
        if (!is_array($arr)) {
            return $arr;
        } elseif (count($arr) == 0) {
            return $arr;
        } elseif (count($arr) == 1 && array_key_exists('_content', $arr)) {
            return $arr['_content'];
        } else {
            foreach ($arr as $key => $element) {
                $arr[$key] = $this->cleanTextNodes($element);
            }
            return ($arr);
        }
    }

    public function contacts(): Contacts
    {
        return app(Contacts::class);
    }

    public function people(): People
    {
        return app(People::class);
    }

    public function favorites(): Favorites
    {
        return app(Favorites::class);
    }

    public function photos(): Photos
    {
        return app(Photos::class);
    }

    public function photosets(): PhotoSets
    {
        return app(PhotoSets::class);
    }

    public function urls(): Urls
    {
        return app(Urls::class);
    }

    public function downloadAlbum(string $albumUrl): Album
    {
        $album = app(Album::class);
        $album->loadFromUrl($albumUrl);

        FlickrRequestDownloadAlbum::dispatch(
            $album->getAlbumId(),
            $album->getUserNsid(),
        )->onQueue('api');

        return $album;
    }

    public function downloadAlbums(string $url): Collection
    {
        if (!$user = $this->urls()->lookupUser($url)) {
            return collect();
        }

        return $this->photosets()->getListAll($user['id'])->each(function ($album) {
            FlickrRequestDownloadAlbum::dispatch(
                $album['id'],
                $album['owner'],
            )->onQueue('api');
        });
    }
}
