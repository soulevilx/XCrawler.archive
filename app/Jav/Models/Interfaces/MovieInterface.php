<?php

namespace App\Jav\Models\Interfaces;

interface MovieInterface
{
    public function getName(): ?string;

    public function getDvdId(): ?string;

    public function getContentId(): ?string;

    public function getGenres(): array;

    public function getPerformers(): array;

    public function refetch(): self;
}
