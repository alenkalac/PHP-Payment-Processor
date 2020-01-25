<?php
namespace App\SymfonyPayments\Selly;

use GuzzleHttp\Client;

class SellyClient {
    protected $endpoint = 'https://selly.io/api/v2';

    /** @var Client $client */
    protected $client;

    public function auth($email, $apikey) {
        $this->client = new Client([
            'base_uri' => $this->endpoint,
            'auth' => [$email, $apikey],
            'headers' => [
                'content-type' => 'application/json'
            ]
        ]);
    }

    /**
     * @param SellyPayment $sellyPayment
     * @return mixed
     */
    public function createPayment($sellyPayment) {
        return $this->postRequest("pay", $sellyPayment->getPaymentAsArray());
    }

    private function getRequest($path, $params) {
        $response = $this->client->get($path, [
            'query' => $params,
        ]);

        return json_decode($response->getBody());
    }

    private function postRequest($path, $params) {
        $response = $this->client->post($path, [
            'json' => $params,
        ]);

        return json_decode($response->getBody());
    }
}