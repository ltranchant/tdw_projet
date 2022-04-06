<?php

namespace App\Repository;

use App\Entity\UtilisateurProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UtilisateurProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method UtilisateurProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method UtilisateurProduit[]    findAll()
 * @method UtilisateurProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UtilisateurProduit::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(UtilisateurProduit $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(UtilisateurProduit $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
      * @return integer Returns the number of article in the basket of a client
      */

    public function countNbArticlesPanier($value)
    {
        return $this->createQueryBuilder('u')
            ->select('sum(u.quantite)')
            ->andWhere('u.utilisateur = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?UtilisateurProduit
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
