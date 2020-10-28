<?php


namespace App\SymfonyPayments\PayPal\Interfaces;


interface PayPalTransactionInterface {
    public function getCaptureUrl();
    public function getRequestBody();
}