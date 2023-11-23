<?php

namespace App\Infrastructure\Spotify\Item\Album;

use DateTimeImmutable;

final readonly class Album
{
    /**
     * @param list<AlbumArtist> $artists
     */
    private function __construct(
        public string $id,
        public string $name,
        public string $uri,
        public int $totalTracks,
        public DateTimeImmutable $releaseDate,
        public array $artists,
    ) {
    }

    public static function fromSpotifyAPIResponse(array $albumData) : self
    {
        $artists = [];

        if (isset($albumData['artists']) === true && count($albumData['artists']) > 0) {
            foreach ($albumData['artists'] as $artist) {
                $artists[] = AlbumArtist::fromSpotifyAPIResponse($artist);
            }
        }

        return new self(
            $albumData['id'],
            $albumData['name'],
            $albumData['uri'],
            $albumData['total_tracks'],
            DateTimeImmutable::createFromFormat('Y-m-d', $albumData['release_date']),
            $artists,
        );
    }
}
