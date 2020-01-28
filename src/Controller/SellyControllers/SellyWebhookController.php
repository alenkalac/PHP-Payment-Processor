<?php
namespace App\Controller\SellyControllers;

use App\Entity\Order;
use App\SymfonyPayments\Interfaces\IWebhook;
use App\SymfonyPayments\Model\Interfaces\IOnlineStoreModel;
use App\SymfonyPayments\Model\SellyModel;
use App\SymfonyPayments\StoreManager;
use App\SymfonyPayments\Utils\FieldUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SellyWebhookController extends AbstractController implements IWebhook {

    private $storeManager;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, StoreManager $storeManager) {
        $this->entityManager = $entityManager;
        $this->storeManager = $storeManager;
    }

    /**
     * @Route("/webhook/selly", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function onHandle(Request $request) {
        $data = FieldUtils::getSafeJson($request->getContent());

        $sellyModel = new SellyModel($data);
        $status = $sellyModel->getWebhookStatus();

        if ($status == 100) {
            //Handle successful purchase
            $this->handlePurchase($sellyModel);
        }

        if ($status == 51) {
            $this->storeManager->handleDispute($sellyModel);
            throw new BadRequestHttpException("Dispute handled.");
        }

        return new Response();
    }

    public function handlePurchase(IOnlineStoreModel $model) {
        $order = new Order();
        $order->setFromModel($model)->setPurchased("PLACEHOLDER");
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}