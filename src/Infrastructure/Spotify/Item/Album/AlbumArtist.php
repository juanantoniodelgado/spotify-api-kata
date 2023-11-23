<?php

namespace App\Infrastructure\Spotify\Item\Album;

final readonly class AlbumArtist
{
    private function __construct(
        public string $id,
        public string $name,
        public string $uri,
    ) {
    }

    public static function fromSpotifyAPIResponse(array $artistData) : self
    {
        return new self(
            $artistData['id'],
            $artistData['name'],
            $artistData['uri'],
        );
    }
}
