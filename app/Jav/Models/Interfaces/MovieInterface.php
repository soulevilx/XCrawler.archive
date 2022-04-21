<?php

namespace App\Jav\Models\Interfaces;

interface MovieInterface
{
    public function getName(): ?string;

    /**
     * DVD ID usually come with format likely: ABW-226
     *
     * @return string|null
     */
    public function getDvdId(): ?string;

    /**
     * Content ID usually come with format likely: nkkvr00029
     *
     * @return string|null
     */
    public function getContentId(): ?string;

    public function getGenres(): array;

    public function getPerformers(): array;
}
