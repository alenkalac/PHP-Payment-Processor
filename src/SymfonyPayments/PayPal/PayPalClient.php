<?php
namespace App\SymfonyPayments\PayPal;

use App\SymfonyPayments\PayPal\Interfaces\PayPalTransactionInterface;
use App\SymfonyPayments\PayPal\Order\PayPalOrderBuilder;
use App\SymfonyPayments\PayPal\Payment\PayPalPaymentBuilder;
use GuzzleHttp\Client;

class PayPalClient {
    private const PAYPAL_SANDBOX_URL = "https://api.sandbox.paypal.com";
    private const PAYPAL_LIVE_URL = "https://api.paypal.com";

    private const AUTH_URL = "/v1/oauth2/token";

    public const FIELD_AMOUNT = "amount";
    public const FIELD_CURRENCY = "currency";
    public const FIELD_RETURN_URL = "return_url";
    public const FIELD_CANCEL_URL = "cancel_url";

    public const FIELD_PAYER_ID = "payerID";
    public const FIELD_ORDER_ID = "orderID";

    private $isSandbox = false;

    /** @var Client */
    private $client;

    public function __construct() {
        $this->client = new Client([
            "headers" => [
                "Content-Type" => "application/json"
            ]
        ]);
    }

    public function authenticate($clientId, $clientSecret) {
        $response = $this->client->post($this->getUri() . self::AUTH_URL, [
            "headers" => [
                "Accept" => "application/json",
                "Content-Type" => "application/x-www-form-urlencoded",
                "Authorization" => "Basic " . base64_encode($clientId . ":" . $clientSecret),
                "Accept-Language" => "en_US",
            ],
            "query" => [
                "grant_type" => "client_credentials"
            ]
        ]);

        if($response->getStatusCode() == 200) {
            $body = $response->getBody()->getContents();
            $json = json_decode($body);

            return $json->access_token;
        }

        throw new \Exception("Bad tokens");
    }

    public function getOrderBuilder() {
        return new PayPalOrderBuilder();
    }

    public function getPaymentBuilder() {
        return new PayPalPaymentBuilder();
    }

    /**
     * @param $isSandbox
     * set to true to disable sandbox mode
     */
    public function setSandboxMode($isSandbox) {
        $this->isSandbox = filter_var($isSandbox, FILTER_VALIDATE_BOOLEAN);
    }

    public function execute($assessToken, PayPalTransactionInterface $transaction) {
        $url = $this->getUri() . $transaction->getUrl();

        $result = $this->client->post($url, [
            "headers" => [
                "Authorization" => "Bearer " . $assessToken,
                "Content-Type" => "application/json"
            ],
            "body" => empty($transaction->getRequestBody()) ? "" : json_encode($transaction->getRequestBody())
        ]);

        return json_decode($result->getBody()->getContents());
    }


    /*
     * private fields
     */
    private function getUri() {
        return $this->isSandbox ? self::PAYPAL_SANDBOX_URL : self::PAYPAL_LIVE_URL;
    }
}
