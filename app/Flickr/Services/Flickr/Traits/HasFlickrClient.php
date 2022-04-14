<?php

namespace App\Flickr\Services\Flickr\Traits;

use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Services\FlickrService;
use ReflectionMethod;

trait HasFlickrClient
{
    /**
     * @param array $args
     * @param string $method
     * @return array
     * @throws FlickrGeneralException
     * @throws \ReflectionException
     */
    public function call(array $args, string $method): array
    {
        $reflect = new \ReflectionClass($this);
        $endPoint = 'flickr.' . strtolower($reflect->getShortName()) . '.' . $method;

        $ref = new ReflectionMethod($this, $method);
        $parameters = [];
        foreach ($ref->getParameters() as $index => $parameter) {
            $parameters[$parameter->name] = $args[$index] ?? $parameter->getDefaultValue();
        }

        return call_user_func(
            [
                app(FlickrService::class), 'request',
            ],
            $endPoint,
            $parameters
        );
    }
}
