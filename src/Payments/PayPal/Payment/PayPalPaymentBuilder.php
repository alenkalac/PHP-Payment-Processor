<?php

namespace App\Payments\PayPal\Payment;

use App\Payments\PayPal\Interfaces\PayPalBuilderInterface;

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

    public function build(): PayPalPayment {
        return $this->payPalPayment;
    }
}