<?php
namespace App\SymfonyPayments\Selly;


class SellyPayment {
    private $arr = [];

    public const GATEWAY_PAYPAL = 'PayPal';
    public const GATEWAY_BITCOIN = 'Bitcoin';

    public const CURRENCY_USD = "USD";
    public const CURRENCY_EUR = "EUR";

    public function __construct($title, $email, $currency, $value, $gateway, $returnURL) {
        $this->arr["title"] = $title;
        $this->arr["email"] = $email;
        $this->arr["currency"] = $currency;
        $this->arr["value"] = $value;
        $this->arr["gateway"] = $gateway;
        $this->arr["return_url"] = $returnURL;
    }

    public function setConfirmations($confirms) {
        $this->arr["confirmations"] = $confirms;
    }

    public function setWhiteLabel($isWhiteLabel) {
        $this->arr["white_label"] = $isWhiteLabel;
    }

    public function setIpAddress($ip) {
        $this->arr["ip_address"] = $ip;
    }

    public function setWebhookUrl($webhookURL) {
        $this->arr["webhook_url"] = $webhookURL;
    }

    public function getPaymentAsArray() {
        return $this->arr;
    }
}