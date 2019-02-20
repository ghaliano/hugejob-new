<?php

namespace App\Services;


use \App\Entity\Publication;
use Doctrine\ORM\EntityManagerInterface;

class PublicationService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function search()
    {
        return $this->em->getRepository(\App\Entity\Publication::class)->search();
    }
}