<?php
namespace App\Controller;

use App\SymfonyPayments\Interfaces\IWebhook;
use App\SymfonyPayments\Model\Interfaces\IOnlineStoreModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SellyWebhookController extends AbstractController implements IWebhook {

    public function onHandle(Request $request) {
        // TODO: Implement onHandle() method.
    }

    public function handlePurchase(IOnlineStoreModel $model) {
        // TODO: Implement handlePurchase() method.
    }
}