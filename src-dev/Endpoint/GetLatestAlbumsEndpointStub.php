<?php

namespace Dev\Endpoint;

use App\Infrastructure\Spotify\Endpoint\GetLatestAlbumsEndpoint;
use App\Infrastructure\Spotify\Item\Album\Album;
use App\Infrastructure\Spotify\SpotifyAPIConnectionException;

class GetLatestAlbumsEndpointStub extends GetLatestAlbumsEndpoint
{
    public bool $throwConnectionExceptionFlag = false;

    private array $data = [];

    public function __construct()
    {
    }

    public function get(int $offset, int $limit) : array
    {
        if ($this->throwConnectionExceptionFlag === true) {
            throw new SpotifyAPIConnectionException();
        }

        return $this->data;
    }

    public function addAlbum(Album $album) : void
    {
        $this->data[] = $album;
    }
}