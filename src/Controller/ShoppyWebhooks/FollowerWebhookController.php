<?php

namespace App\Controller\ShoppyWebhooks;

use App\Entity\Order;
use App\SymfonyPayments\Interfaces\IWebhook;
use App\SymfonyPayments\Logger\EnvAwareLogger;
use App\SymfonyPayments\Model\ShoppyModel;
use App\SymfonyPayments\ShoppyClient;
use App\SymfonyPayments\Utils\FieldUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ShoppyWebhookController extends AbstractController implements IWebhook
{
    private $entityManager;
    private $logger;
    private $shoppyClient;

    public function __construct(EntityManagerInterface $entityManager, EnvAwareLogger $envAwareLogger, ShoppyClient $client) {
        $this->entityManager = $entityManager;
        $this->logger = $envAwareLogger->getLogger();
        $this->shoppyClient = $client;
    }

    /**
     * @Route("/webhook/shoppy", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function onHandle(Request $request) {
        $this->shoppyClient->verifyHmac($request->getContent(), $_ENV["SHOPPY_API_SECRET"], $request->headers->get('X-SHOPPY-SIGNATURE', ''));

        $data = FieldUtils::getSafeJson($request->getContent());

        $shoppyModel = new ShoppyModel($data);
        $webhookStatus = $shoppyModel->getWebhookStatus();

        if ($webhookStatus == 100) {

            //Handle successful purchase
            $this->handlePurchase($shoppyModel);
        }

        if ($webhookStatus == 200) {
            $this->shoppyClient->handleDispute($shoppyModel);
            throw new BadRequestHttpException("Dispute handled.");
        }

        return new Response();
    }

    public function handlePurchase(ShoppyModel $shoppyModel) {
        $order = new Order();
        $order->setFromModel($shoppyModel)->setPurchased("PLACEHOLDER");
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}
