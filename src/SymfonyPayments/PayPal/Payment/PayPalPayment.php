<?php
namespace App\SymfonyPayments\PayPal\Payment;

use App\SymfonyPayments\PayPal\Interfaces\PayPalTransactionInterface;

class PayPalPayment implements PayPalTransactionInterface {
    private const PAYMENT_URL = "/v2/checkout/orders/";
    private const CAPTURE_PART = "/capture";

    private $payerId;
    private $orderId;

    public function setPayerId($payerId) {
        $this->payerId = $payerId;
    }

    public function getPayerId() {
        return $this->payerId;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getRequestBody(){
        return [];
    }

    public function getUrl(){
        return self::PAYMENT_URL . $this->orderId . self::CAPTURE_PART;
    }
}
