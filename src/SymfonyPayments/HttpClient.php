<?php
namespace App\SymfonyPayments;

use GuzzleHttp\Client;

class HttpClient {
    private $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function get($url, $options = []) {
        $res = $this->client->get($url, $options);
        return $res->getBody();
    }

    public function post($url, $options = []) {
        $res = $this->client->post($url, $options);
        return $res->getBody();
    }
}
