<?php

namespace App\Jav\Models\Traits;

trait HasCover
{
    public function getCover(): string
    {
        if ($this->cover) {
            return $this->cover;
        }

        return 'https://via.placeholder.com/210';
    }
}
