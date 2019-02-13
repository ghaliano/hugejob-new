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

    public function search($page){
        $qb = $this->createQueryBuilder('offer');
        $qb
            ->select('offer')
            ->setFirstResult($page-1)
            ->setMaxResults(1)
        ;

        return new Paginator($qb);
    }
}