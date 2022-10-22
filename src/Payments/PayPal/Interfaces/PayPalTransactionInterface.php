<?php


namespace App\Payments\PayPal\Interfaces;


interface PayPalTransactionInterface {
    public function getUrl();
    public function getRequestBody();
}
