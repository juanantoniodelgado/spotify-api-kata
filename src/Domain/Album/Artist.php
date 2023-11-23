<?php

namespace App\Domain\Album;

final readonly class Artist
{
    public function __construct(
        public ArtistExternalId $externalId,
        public string $name,
        public ArtistUri $artistUri,
    ) {
    }
}
