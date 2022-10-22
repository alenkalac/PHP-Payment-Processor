<?php
namespace App\Payments;

use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class HttpClient {
    private $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function get($url, $options = []): StreamInterface {
        $res = $this->client->get($url, $options);
        return $res->getBody();
    }

    public function post($url, $options = []): StreamInterface {
        $res = $this->client->post($url, $options);
        return $res->getBody();
    }
}
