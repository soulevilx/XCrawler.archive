<?php

namespace App\Flickr\Services\Flickr;

use App\Flickr\Services\FlickrService;
use Illuminate\Support\Str;
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

        $response = call_user_func(
            [
                $this->service, 'request',
            ],
            $this->buildPath($method),
            $parameters
        );


        if (isset($response[$this->namespace])) {
            $singular = Str::singular($this->namespace);
            $response[$this->namespace][$singular] = collect($response[$this->namespace][$singular]);
            return $response[$this->namespace];
        }
        return $response;
    }

    public function __call($name, $arguments)
    {
        $baseMethod = str_replace('All', '', $name);
        if (!method_exists($this, $baseMethod)) {
            return;
        }

        $init = call_user_func_array([$this, $baseMethod], $arguments);
        $singular = Str::singular($this->namespace);

        $list = $init[$singular];

        $ref = new ReflectionMethod($this, $baseMethod);


        for ($page = 2; $page <= $init['pages']; $page++) {
            $parameters = [];
            foreach ($ref->getParameters() as $index => $parameter) {
                if ($parameter->name === 'page') {
                    $parameters[$index] = $page;
                    continue;
                }
                $parameters[$index] = $args[$index] ?? $parameter->getDefaultValue();
            }

            $data = call_user_func_array([$this, $baseMethod], $parameters);
            $list = $list->merge($data[$singular]);
        }

        return $list;
    }
}
