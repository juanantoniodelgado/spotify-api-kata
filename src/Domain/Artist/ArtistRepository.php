<?php

namespace App\Domain\Artist;

use App\Domain\Album\Artist;
use App\Domain\Album\ArtistExternalId;

interface ArtistRepository
{
    /**
     * @throws ArtistNotFoundException
     */
    public function findByExternalIdOrThrow(ArtistExternalId $externalId) : Artist;

    public function save(Artist $artist) : void;
}
