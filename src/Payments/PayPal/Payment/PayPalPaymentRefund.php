<?php
namespace App\Payments\PayPal\Payment;

use App\Payments\PayPal\Interfaces\PayPalTransactionInterface;

class PayPalPaymentRefund implements PayPalTransactionInterface {

    private const PAYMENT_URL = "/v2/payments/captures/";
    private const END_PART = "/refund";

    private $transactionId;

    public function __construct($transactionId) {
        $this->transactionId = $transactionId;
    }

    public function getUrl(): string {
        return self::PAYMENT_URL . $this->transactionId . self::END_PART;
    }

    public function getRequestBody(): string {
        return "";
    }
}
