<?php

namespace App\Domain\Album;

final readonly class ArtistExternalId
{
    public function __construct(
        public string $artistId,
    ) {
    }
}
