<?php


namespace App\SymfonyPayments\PayPal\Interfaces;


interface PayPalTransactionInterface {
    public function getUrl();
    public function getRequestBody();
}
