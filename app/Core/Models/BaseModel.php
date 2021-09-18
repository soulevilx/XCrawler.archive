<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class BaseModel extends Model
{
    private ReflectionClass $reflectionClass;

    public function __construct(array $attributes = [])
    {
        $this->reflectionClass = new ReflectionClass($this);
        foreach ($this->reflectionClass->getTraits() as $trait) {
            $method = 'load' . $trait->getShortName(). 'Trait';
            if (!method_exists($this, $method)) {
                continue;
            }
            $this->{$method}();
        }

        parent::__construct($attributes);
    }
}
