<?php

namespace App\Controller;

use App\Entity\HealthCheck;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HealthCheckController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/api/healthy')]
    public function onHealthCheck(): Response {
        $healthCheckRepository = $this->entityManager->getRepository(HealthCheck::class);

        try {
            $healthCheckRepository->find(1);
        } catch (\Exception $e) {
            return new Response("501", 500);
        }

        return new Response();
    }
}
