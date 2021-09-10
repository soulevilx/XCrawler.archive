<?php

namespace App\Flickr\Services;

use App\Flickr\Services\Flickr\Contacts;
use App\Flickr\Services\Flickr\People;
use App\Flickr\Services\Flickr\Photos;
use Illuminate\Support\Facades\DB;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Uri\UriInterface;
use OAuth\Common\Storage\Memory;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth1\Token\StdOAuth1Token;
use OAuth\OAuth2\Token\TokenInterface;
use OAuth\ServiceFactory;

class FlickrService
{
    public const SERVICE = 'flickr';

    protected TokenStorageInterface $oauthTokenStorage;
    /**
     * @var TokenInterface
     */
    protected $oauthRequestToken;

    public function __construct(private ?string $apiKey = null, private ?string $secret = null)
    {
        $this->oauthTokenStorage = new Memory();
    }

    protected function getClient(string $callbackUrl = 'oob')
    {
        $integration = DB::table('integrations')
            ->where([
                'service' => FlickrService::SERVICE,
            ])->first();

        if ($integration) {
            $token = new StdOAuth1Token();
            $token->setAccessToken($integration->token);
            $token->setAccessTokenSecret($integration->token_secret);
            $this->oauthTokenStorage->storeAccessToken('Flickr', $token);
        }

        $credentials = new Credentials($this->apiKey, $this->secret, $callbackUrl);

        return app(ServiceFactory::class)->createService('Flickr', $credentials, $this->oauthTokenStorage);
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

    public function request(string $path, array $params): array
    {
        $params = array_filter($params);
        $response = $this->getClient()->requestJson($path, 'POST', $params);

        $jsonResponse = json_decode($response, true);

        if (null === $jsonResponse) {
            throw new \Exception("Unable to decode Flickr response to $path request: " . $response);
        }

        $jsonResponse = $this->cleanTextNodes($jsonResponse);

        if ($jsonResponse['stat'] === 'fail') {
            throw new \Exception($jsonResponse['message'], $jsonResponse['code']);
        }

        unset($jsonResponse['stat']);

        return $jsonResponse;
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
        return new Contacts($this);
    }

    public function people(): People
    {
        return new People($this);
    }

    public function photos(): Photos
    {
        return new Photos($this);
    }
}