<?php

namespace App\Infrastructure\Spotify\Item;

use DateTimeImmutable;

final class Album
{
    private function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $uri,
        public readonly int $totalTracks,
        public readonly DateTimeImmutable $releaseDate,
    ) {
    }

    public static function fromSpotifyAPIResponse(array $albumData) : self
    {
        return new self(
            $albumData['id'],
            $albumData['name'],
            $albumData['uri'],
            $albumData['total_tracks'],
            DateTimeImmutable::createFromFormat('Y-m-d', $albumData['release_date']),
        );
    }
}
