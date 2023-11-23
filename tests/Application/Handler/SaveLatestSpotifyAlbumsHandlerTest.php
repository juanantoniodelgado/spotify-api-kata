<?php

namespace App\Tests\Application\Handler;

use App\Application\Command\SaveLatestSpotifyAlbumsCommand;
use App\Application\Handler\SaveLatestSpotifyAlbumsHandler;
use App\Domain\Album\Album;
use App\Domain\Album\AlbumExternalId;
use App\Domain\Album\AlbumRepository;
use App\Domain\Album\AlbumUri;
use App\Domain\Artist\ArtistRepository;
use App\Infrastructure\Spotify\Item\Album\Album as AlbumItem;
use App\Infrastructure\Spotify\Endpoint\GetLatestAlbumsEndpoint;
use DateTimeImmutable;
use Dev\Endpoint\GetLatestAlbumsEndpointStub;
use Dev\InMemoryLogger;
use Dev\Repository\InMemoryAlbumRepository;
use Dev\Repository\InMemoryArtistRepository;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SaveLatestSpotifyAlbumsHandlerTest extends TestCase
{
    private SaveLatestSpotifyAlbumsHandler $handler;
    private GetLatestAlbumsEndpoint $endpoint;
    private AlbumRepository $albumRepository;
    private ArtistRepository $artistRepository;
    private LoggerInterface $logger;

    public function setUp(): void
    {
        $this->handler = new SaveLatestSpotifyAlbumsHandler(
            $this->endpoint = new GetLatestAlbumsEndpointStub(),
            $this->albumRepository = new InMemoryAlbumRepository(),
            $this->artistRepository = new InMemoryArtistRepository(),
            $this->logger = new InMemoryLogger(),
        );

        parent::setUp();
    }

    /**
     * @test
     */
    public function it_should_log_if_spotify_connection_fails() : void
    {
        // Arrange
        $this->endpoint->throwConnectionExceptionFlag = true;
        self::assertCount(0, $this->logger->getAllLogs()['warning']);

        // Act
        $this->handler->__invoke(new SaveLatestSpotifyAlbumsCommand(1));

        // Assert
        self::assertCount(1, $this->logger->getAllLogs()['warning']);
    }

    /**
     * @test
     */
    public function it_should_not_persist_album_if_already_persisted() : void
    {
        // Arrange
        $this->endpoint->throwConnectionExceptionFlag = false;
        $this->endpoint->addAlbum(AlbumItem::fromSpotifyAPIResponse([
            'id' => 'external-id',
            'name' => 'album-name',
            'uri' => 'spotify:test:uri',
            'total_tracks' => 12,
            'release_date' => '2023-11-23',
            [],
        ]));
        $this->albumRepository->save(
            new Album(
                new AlbumExternalId('external-id'),
                'album-name',
                AlbumUri::fromUri('spotify:test:uri'),
                12,
                DateTimeImmutable::createFromFormat('Y-m-d', '2023-11-23'),
                []
            ),
        );
        self::assertCount(1, $this->albumRepository->findAll());

        // Act
        $this->handler->__invoke(new SaveLatestSpotifyAlbumsCommand(1));

        // Assert
        self::assertCount(1, $this->albumRepository->findAll());
    }

    /**
     * @test
     */
    public function it_should_persist_an_album() : void
    {
        // Arrange
        $this->endpoint->throwConnectionExceptionFlag = false;
        $this->endpoint->addAlbum(AlbumItem::fromSpotifyAPIResponse([
            'id' => 'external-id',
            'name' => 'album-name',
            'uri' => 'spotify:test:uri',
            'total_tracks' => 12,
            'release_date' => '2023-11-23',
            [],
        ]));

        // Act
        $this->handler->__invoke(new SaveLatestSpotifyAlbumsCommand(1));

        // Assert
        self::assertCount(1, $this->albumRepository->findAll());
    }
}