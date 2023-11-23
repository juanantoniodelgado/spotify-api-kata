<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Album\Artist;
use App\Domain\Album\ArtistExternalId;
use App\Domain\Album\ArtistUri;
use App\Domain\Artist\ArtistNotFoundException;
use App\Domain\Artist\ArtistRepository;
use App\Infrastructure\Persistence\Entity\DoctrineArtist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineArtistRepository extends ServiceEntityRepository implements ArtistRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctrineArtist::class);
    }

    /**
     * @throws ArtistNotFoundException
     */
    public function findByExternalIdOrThrow(ArtistExternalId $externalId) : Artist
    {
        try {
            $doctrineArtist = $this->createQueryBuilder('da')
                ->where('da.externalId = :externalId')
                ->setParameter('externalId', $externalId->artistId)
                ->getQuery()->getSingleResult();

            return new Artist(
                new ArtistExternalId($doctrineArtist->getId()),
                $doctrineArtist->getName(),
                ArtistUri::fromUri($doctrineArtist->getUri()),
            );
        } catch (NonUniqueResultException|NoResultException) {
            throw new ArtistNotFoundException();
        }
    }

    public function save(Artist $artist) : void
    {
        $this->getEntityManager()->persist(
            new DoctrineArtist(
                $artist->externalId->artistId,
                $artist->name,
                $artist->artistUri->toUri(),
                [],
            ),
        );
        $this->getEntityManager()->flush();
    }
}