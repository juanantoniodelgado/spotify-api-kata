<?php

namespace App\Domain\Album;

final readonly class AlbumExternalId
{
    public function __construct(
        public string $albumId,
    ) {
    }
}
