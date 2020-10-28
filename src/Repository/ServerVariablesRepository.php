<?php

namespace App\Repository;

use App\Entity\ServerVariables;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ServerVariables|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServerVariables|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServerVariables[]    findAll()
 * @method ServerVariables[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerVariablesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServerVariables::class);
    }
}