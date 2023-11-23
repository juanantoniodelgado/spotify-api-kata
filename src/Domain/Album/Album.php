<?php

namespace App\Domain\Album;

use DateTimeImmutable;

final readonly class Album
{
    /**
     * @param list<Artist> $artists
     */
    public function __construct(
        public AlbumExternalId $externalId,
        public string $name,
        public AlbumUri $albumUri,
        public int $totalTracks,
        public DateTimeImmutable $releaseDate,
        public array $artists,
    ) {
    }
}
