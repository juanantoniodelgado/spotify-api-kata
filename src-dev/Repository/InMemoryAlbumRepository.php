<?php

namespace Dev\Repository;

use App\Domain\Album\Album;
use App\Domain\Album\AlbumExternalId;
use App\Domain\Album\AlbumNotFoundException;
use App\Domain\Album\AlbumRepository;

class InMemoryAlbumRepository implements AlbumRepository
{
    /**
     * @var list<Album> $data
     */
    private array $data = [];

    /**
     * @throws AlbumNotFoundException
     */
    public function findByExternalIdOrThrow(AlbumExternalId $externalId) : Album
    {
        foreach ($this->data as $item) {
            if ($externalId->albumId === $item->externalId->albumId) {
                return $item;
            }
        }

        throw new AlbumNotFoundException();
    }

    public function save(Album $album): void
    {
        $this->data[] = $album;
    }

    public function findAll() : array
    {
        return $this->data;
    }
}