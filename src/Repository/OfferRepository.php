<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function search($filters){
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');

        if (isset($filters['term'])){
            $qb->andWhere('s.name like :term');
            $qb->setParameter('term', '%'.$filters['term'].'%');
        }

        if (isset($filters['companies'])){
            $qb->andWhere('s.company IN (:company)');
            $qb->setParameter('company', $filters['companies']);
        }

        $qb
            ->setFirstResult(0)
            ->setMaxResults(20)
        ;

        return new Paginator($qb);
    }
}