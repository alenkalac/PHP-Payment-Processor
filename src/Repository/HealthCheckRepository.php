<?php

namespace App\Repository;

use App\Entity\HealthCheck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method HealthCheck|null find($id, $lockMode = null, $lockVersion = null)
 * @method HealthCheck|null findOneBy(array $criteria, array $orderBy = null)
 * @method HealthCheck[]    findAll()
 * @method HealthCheck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HealthCheckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HealthCheck::class);
    }
}
