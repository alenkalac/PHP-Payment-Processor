<?php

namespace App\Repository;

use App\Entity\PayPalOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PayPalOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayPalOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayPalOrder[]    findAll()
 * @method PayPalOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayPalOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayPalOrder::class);
    }
}
