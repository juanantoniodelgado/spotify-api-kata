<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Infrastructure\Persistence\Repository\DoctrineAlbumRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

#[Table(name: 'album')]
#[Entity(repositoryClass: DoctrineAlbumRepository::class)]
class DoctrineAlbum
{
    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(name: 'id', type: 'integer')]
    private int $id;

    #[Column(name: 'external_id', type: 'string', length: 62, unique: true)]
    private string $externalId;

    #[Column(name: 'name', type: 'string', length: 255)]
    private string $name;

    #[Column(name: 'uri', type: 'string', length: 255, unique: true)]
    private string $uri;

    #[Column(name: 'total_tracks', type: 'integer', length: 5)]
    private int $totalTracks;

    #[Column(name: 'release_date', type: 'datetime_immutable')]
    private DateTimeImmutable $releaseDate;

    #[ManyToMany(targetEntity: DoctrineArtist::class, inversedBy: 'albums')]
    #[JoinTable(name: 'album_artist')]
    private Collection $artists;

    public function __construct(
        string $externalId,
        string $name,
        string $uri,
        int $totalTracks,
        DateTimeImmutable $releaseDate,
        array $artists,
    ) {
        $this->id = 0;
        $this->externalId = $externalId;
        $this->name = $name;
        $this->uri = $uri;
        $this->totalTracks = $totalTracks;
        $this->releaseDate = $releaseDate;
        $this->artists = new ArrayCollection($artists);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    public function getTotalTracks(): int
    {
        return $this->totalTracks;
    }

    public function setTotalTracks(int $totalTracks): void
    {
        $this->totalTracks = $totalTracks;
    }

    public function getReleaseDate(): DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(DateTimeImmutable $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function setArtists(Collection $artists): void
    {
        $this->artists = $artists;
    }
}
