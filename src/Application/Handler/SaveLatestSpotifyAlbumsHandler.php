<?php

namespace App\Application\Handler;

use App\Application\Command\SaveLatestSpotifyAlbumsCommand;
use App\Domain\Album\Album;
use App\Domain\Album\AlbumExternalId;
use App\Domain\Album\AlbumNotFoundException;
use App\Domain\Album\AlbumRepository;
use App\Domain\Album\AlbumUri;
use App\Infrastructure\Spotify\Endpoint\GetLatestAlbumsEndpoint;
use App\Infrastructure\Spotify\SpotifyAPIConnectionException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SaveLatestSpotifyAlbumsHandler
{
    public function __construct(
        private readonly GetLatestAlbumsEndpoint $latestAlbumsEndpoint,
        private readonly AlbumRepository $repository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(SaveLatestSpotifyAlbumsCommand $command) : void
    {
        try {
            $retrievedAlbums = $this->latestAlbumsEndpoint->get(0, $command->amountLimit);
        } catch (SpotifyAPIConnectionException $exception) {
            $this->logger->warning('Failed to retrieve latest new albums from Spotify', [
                $exception->getCode(),
                $exception->getMessage()
            ]);

            return;
        }

        foreach ($retrievedAlbums as $retrievedAlbum) {
            try {
                $this->repository->findByExternalIdOrThrow(new AlbumExternalId($retrievedAlbum->id));
                continue;
            } catch (AlbumNotFoundException) {
                // continue
            }

            try {
                $album = new Album(
                    new AlbumExternalId($retrievedAlbum->id),
                    $retrievedAlbum->name,
                    AlbumUri::fromUri($retrievedAlbum->uri),
                    $retrievedAlbum->totalTracks,
                    $retrievedAlbum->releaseDate,
                );

                $this->repository->save($album);
            } catch (InvalidArgumentException $exception) {
                $this->logger->warning('Failed to instantiate new Spotify Album', [
                    $exception->getCode(),
                    $exception->getMessage()
                ]);
            }
        }
    }
}
