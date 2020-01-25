<?php

namespace App\SymfonyPayments\Interfaces;

use App\SymfonyPayments\Model\Interfaces\IOnlineStoreModel;
use Symfony\Component\HttpFoundation\Request;

interface IWebhook {
    public function onHandle(Request $request);
    public function handlePurchase(IOnlineStoreModel $shoppyModel);
}