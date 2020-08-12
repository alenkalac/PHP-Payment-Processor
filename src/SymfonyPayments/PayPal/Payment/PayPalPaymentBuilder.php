<?php

namespace App\SymfonyPayments\PayPal\Payment;

use App\SymfonyPayments\PayPal\interfaces\PayPalBuilderInterface;

class PayPalPaymentBuilder implements PayPalBuilderInterface {

    private $payPalPayment;

    public function __construct() {
        $this->payPalPayment = new PayPalPayment();
    }

    public function setPayerId($payerId): PayPalPaymentBuilder {
        $this->payPalPayment->setPayerId($payerId);
        return $this;
    }

    public function setOrderId($orderId): PayPalPaymentBuilder{
        $this->payPalPayment->setOrderId($orderId);
        return $this;
    }

    public function build() {
        return $this->payPalPayment;
    }
}