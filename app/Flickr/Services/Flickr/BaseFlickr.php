<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Exceptions\FlickrGeneralException;
use App\Flickr\Services\FlickrService;
use ReflectionMethod;

class BaseFlickr
{
    protected string $namespace;

    public function __construct(protected FlickrService $service)
    {
        $reflect = new \ReflectionClass($this);
        $this->namespace = strtolower($reflect->getShortName());
    }

    protected function buildPath(string $method): string
    {
        return 'flickr.' . $this->namespace . '.' . $method;
    }

    /**
     * @param array $args
     * @param string $method
     * @return array
     * @throws FlickrGeneralException
     * @throws \ReflectionException
     */
    protected function call(array $args, string $method): array
    {
        $ref = new ReflectionMethod($this, $method);
        $parameters = [];
        foreach ($ref->getParameters() as $index => $parameter) {
            $parameters[$parameter->name] = $args[$index] ?? $parameter->getDefaultValue();
        }

        $data = call_user_func(
            [
                $this->service, 'request',
            ],
            $this->buildPath($method),
            $parameters
        );

        if (isset($data['stat']) && $data['stat'] === 'fail') {
            throw new FlickrGeneralException($data['message'] ?? '', $data['code'] ?? null);
        }

        return $data;
    }
}
