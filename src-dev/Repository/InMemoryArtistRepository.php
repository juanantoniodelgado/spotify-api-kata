<?php

namespace Dev\Repository;

use App\Domain\Album\Artist;
use App\Domain\Album\ArtistExternalId;
use App\Domain\Artist\ArtistRepository;

class InMemoryArtistRepository implements ArtistRepository
{

    public function findByExternalIdOrThrow(ArtistExternalId $externalId): Artist
    {
        // TODO: Implement findByExternalIdOrThrow() method.
    }

    public function save(Artist $artist): void
    {
        // TODO: Implement save() method.
    }
}