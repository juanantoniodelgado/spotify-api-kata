<?php

namespace App\Domain\Album;

interface AlbumRepository
{
    /**
     * @throws AlbumNotFoundException
     */
    public function findByExternalIdOrThrow(AlbumExternalId $externalId) : Album;

    public function save(Album $album) : void;
}
