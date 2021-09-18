<?php

namespace App\Jav\Services\Traits;

trait HasAttributes
{
    protected array $attributes;

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function defaultAttribute(string $key, $value)
    {
        $this->attributes[$key] = $this->attributes[$key] ?? $value;
    }
}
