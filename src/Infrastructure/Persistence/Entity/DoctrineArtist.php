<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Infrastructure\Persistence\Repository\DoctrineArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

#[Table(name: 'artist')]
#[Entity(repositoryClass: DoctrineArtistRepository::class)]
class DoctrineArtist
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

    #[ManyToMany(targetEntity: DoctrineAlbum::class, mappedBy: 'artists')]
    private Collection $albums;
    
    public function __construct(string $externalId, string $name, string $uri, array $albums)
    {
        $this->id = 0;
        $this->externalId = $externalId;
        $this->name = $name;
        $this->uri = $uri;
        $this->albums = new ArrayCollection($albums);
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getExternalId() : string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId) : void
    {
        $this->externalId = $externalId;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    public function getUri() : string
    {
        return $this->uri;
    }

    public function setUri(string $uri) : void
    {
        $this->uri = $uri;
    }

    public function getAlbums() : Collection
    {
        return $this->albums;
    }

    public function setAlbums(Collection $albums) : void
    {
        $this->albums = $albums;
    }
}
