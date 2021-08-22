<?php

namespace App\Jav\Models\Interfaces;

interface MovieInterface
{
    public function getName(): ?string;

    public function getDvdId(): ?string;

    public function getGenres(): array;

    public function getPerformers(): array;

    public function isDownloadable(): bool;

    public function refetch(): self;
}
