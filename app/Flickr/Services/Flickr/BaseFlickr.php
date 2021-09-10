<?php

namespace App\Flickr\Services\Flickr;

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
     * @throws \ReflectionException
     */
    protected function call(array $args, string $method): array
    {
        $ref = new ReflectionMethod($this, $method);
        $parameters = [];
        foreach ($ref->getParameters() as $index => $parameter) {
            $parameters[$parameter->name] = $args[$index] ?? $parameter->getDefaultValue();
        }

        return call_user_func(
            [
                $this->service,'request',
            ],
            $this->buildPath($method),
            $parameters
        );
    }
}
