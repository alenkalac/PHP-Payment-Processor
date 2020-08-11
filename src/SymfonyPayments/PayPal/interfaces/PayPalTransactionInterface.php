<?php


namespace App\SymfonyPayments\PayPal\interfaces;


interface PayPalTransactionInterface {
    public function getCaptureUrl();
    public function getRequestBody();
}