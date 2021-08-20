<?php

namespace App\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory as AbstractFactory;
use Illuminate\Support\Str;

class Factory extends AbstractFactory
{
    public function definition()
    {
        return [];
    }

    public function as(string | array $states): self
    {
        if (is_array($states) && !empty($states)) {
            $state = array_pop($states);

            return $this->as($states)->as($state);
        }

        if (is_string($states)) {
            $method = 'as'.Str::camel($states);
            if (//method_exists($this, $method) &&
            is_callable([$this, $method])) {
                return $this->{$method}();
            }
        }

        return $this;
    }
}
