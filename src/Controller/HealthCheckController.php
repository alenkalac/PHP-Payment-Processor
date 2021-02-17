<?php

namespace App\Controller;

use App\Entity\HealthCheck;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/healthy")
     */
    public function onHealthCheck() {
        $healthCheckRepository = $this->entityManager->getRepository(HealthCheck::class);

        try {
            $healthCheckRepository->find(1);
        } catch (\Exception $e) {
            return new Response("501", 500);
        }

        return new Response();
    }
}
