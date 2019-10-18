<?php

namespace App\Repository;

use App\Entity\FigureForum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FigureForum|null find($id, $lockMode = null, $lockVersion = null)
 * @method FigureForum|null findOneBy(array $criteria, array $orderBy = null)
 * @method FigureForum[]    findAll()
 * @method FigureForum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigureForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FigureForum::class);
    }

    // /**
    //  * @return FigureForum[] Returns an array of FigureForum objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FigureForum
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
