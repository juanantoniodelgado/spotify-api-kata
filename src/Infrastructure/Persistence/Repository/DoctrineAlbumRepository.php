<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Album\Album as DomainAlbum;
use App\Domain\Album\AlbumExternalId;
use App\Domain\Album\AlbumNotFoundException;
use App\Domain\Album\AlbumRepository;
use App\Domain\Album\AlbumUri;
use App\Infrastructure\Persistence\Entity\DoctrineAlbum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineAlbumRepository extends ServiceEntityRepository implements AlbumRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctrineAlbum::class);
    }

    public function findByExternalIdOrThrow(AlbumExternalId $externalId) : DomainAlbum
    {
        try {
            $doctrineAlbum = $this->createQueryBuilder('da')
                ->where('da.externalId = :externalId')
                ->setParameter('externalId', $externalId->albumId)
                ->getQuery()->getSingleResult();

            return new DomainAlbum(
                new AlbumExternalId($doctrineAlbum->getId()),
                $doctrineAlbum->getName(),
                AlbumUri::fromUri($doctrineAlbum->getUri()),
                $doctrineAlbum->getTotalTracks(),
                $doctrineAlbum->getReleaseDate(),
            );
        } catch (NonUniqueResultException|NoResultException) {
            throw new AlbumNotFoundException();
        }
    }

    public function save(DomainAlbum $album) : void
    {
        $this->getEntityManager()->persist(new DoctrineAlbum(
            $album->externalId->albumId,
            $album->name,
            $album->albumUri->toUri(),
            $album->totalTracks,
            $album->releaseDate,
        ));

        $this->getEntityManager()->flush();
    }
}
