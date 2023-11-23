<?php

namespace App\Application\Command;

final readonly class SaveLatestSpotifyAlbumsCommand
{
    public function __construct(
        public int $amountLimit,
    ) {
    }
}
