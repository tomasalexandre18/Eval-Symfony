<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    //    /**
    //     * @return Annonce[] Returns an array of Annonce objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Annonce
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByName($name): array {
        return $this->findByNameAndUser(null, $name);
    }
    public function findByNameAndUser(?User $user, ?string $name): array {
        $query = $this->createQueryBuilder('a');
        if ($user) {
            $query->andWhere("a.user = :user")
                ->setParameter("user", $user->getId());
        }
        if ($name) {
            $query->andWhere('a.titre LIKE :name')
                ->setParameter(':name', "%$name%");
        }

        return $query
            ->getQuery()
            ->getResult();
    }
}
