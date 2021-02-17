<?php
namespace App\SymfonyPayments\PayPal\Payment;

use App\SymfonyPayments\PayPal\Interfaces\PayPalTransactionInterface;

class PayPalPaymentRefund implements PayPalTransactionInterface {

    private const PAYMENT_URL = "/v2/payments/captures/";
    private const ENDPART_PART = "/refund";

    private $transactionId;

    public function __construct($transactionId) {
        $this->transactionId = $transactionId;
    }

    public function getUrl() {
        return self::PAYMENT_URL . $this->transactionId . self::ENDPART_PART;
    }

    public function getRequestBody() {
        return "";
    }
}
