<?php

namespace App\Repository;

use App\Entity\Evenements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\BrowserKit\Response;

/**
 * @extends ServiceEntityRepository<Evenements>
 */
class EvenementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenements::class);
    }


        #[Route('/category', name: 'app_evenements_find_by_category', methods: ['GET'])]
    public function findByCategoryName(Evenements $categoryName): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.category', 'c')
            ->where('c.name = :name')
            ->setParameter('name', $categoryName)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Evenements[] Returns an array of Evenements objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Evenements
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
