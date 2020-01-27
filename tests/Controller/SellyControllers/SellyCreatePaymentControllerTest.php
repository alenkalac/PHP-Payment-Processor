<?php
namespace App\Tests\Controller\SellyControllers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SellyCreatePaymentControllerTest extends WebTestCase {

    public function testCreatePayment() {
        $client = static::createClient();

        $client->request("GET", "/api/selly/payment", [
            "title" => "TestProduct",
            "email" => "testEmail@gmail.com",
            "value" => "10",
            "currency" => "USD",
            "gateway" => "PayPal",
            "returnURL" => "https://test.com"
        ]);

        $statusCode = $client->getResponse()->getStatusCode();
        $content = $client->getResponse()->getContent();

        self::assertContains("https://selly.io", $content);
        self::assertEquals(200, $statusCode);
    }

    public function testCreatePaymentMissingTitleReturnsError() {
        $client = static::createClient();

        $client->request("GET", "/api/selly/payment", [
            "email" => "testEmail@gmail.com",
            "value" => "10",
            "currency" => "USD",
            "gateway" => "PayPal",
            "returnURL" => "https://test.com"
        ]);

        $statusCode = $client->getResponse()->getStatusCode();
        $content = $client->getResponse()->getContent();

        self::assertContains("error", $content);
        self::assertContains("Missing Required Fields", $content);
        self::assertEquals(200, $statusCode);
    }

    public function testCreatePaymentMissingEmailReturnsError() {
        $client = static::createClient();

        $client->request("GET", "/api/selly/payment", [
            "title" => "TestProduct",
            "value" => "10",
            "currency" => "USD",
            "gateway" => "PayPal",
            "returnURL" => "https://test.com"
        ]);

        $statusCode = $client->getResponse()->getStatusCode();
        $content = $client->getResponse()->getContent();

        self::assertContains("error", $content);
        self::assertContains("Missing Required Fields", $content);
        self::assertEquals(200, $statusCode);
    }

    public function testCreatePaymentMissingValueReturnsError() {
        $client = static::createClient();

        $client->request("GET", "/api/selly/payment", [
            "title" => "TestProduct",
            "email" => "testEmail@gmail.com",
            "currency" => "USD",
            "gateway" => "PayPal",
            "returnURL" => "https://test.com"
        ]);

        $statusCode = $client->getResponse()->getStatusCode();
        $content = $client->getResponse()->getContent();

        self::assertContains("error", $content);
        self::assertContains("Missing Required Fields", $content);
        self::assertEquals(200, $statusCode);
    }

    public function testCreatePaymentMissingCurrencyReturnsError() {
        $client = static::createClient();

        $client->request("GET", "/api/selly/payment", [
            "title" => "TestProduct",
            "email" => "testEmail@gmail.com",
            "value" => "10",
            "gateway" => "PayPal",
            "returnURL" => "https://test.com"
        ]);

        $statusCode = $client->getResponse()->getStatusCode();
        $content = $client->getResponse()->getContent();

        self::assertContains("error", $content);
        self::assertContains("Missing Required Fields", $content);
        self::assertEquals(200, $statusCode);
    }

    public function testCreatePaymentMissingGatewayReturnsError() {
        $client = static::createClient();

        $client->request("GET", "/api/selly/payment", [
            "title" => "TestProduct",
            "email" => "testEmail@gmail.com",
            "value" => "10",
            "currency" => "USD",
            "returnURL" => "https://test.com"
        ]);

        $statusCode = $client->getResponse()->getStatusCode();
        $content = $client->getResponse()->getContent();

        self::assertContains("error", $content);
        self::assertContains("Missing Required Fields", $content);
        self::assertEquals(200, $statusCode);
    }

    public function testCreatePaymentMissingReturnURLReturnsError() {
        $client = static::createClient();

        $client->request("GET", "/api/selly/payment", [
            "title" => "TestProduct",
            "email" => "testEmail@gmail.com",
            "value" => "10",
            "currency" => "USD",
            "gateway" => "PayPal"
        ]);

        $statusCode = $client->getResponse()->getStatusCode();
        $content = $client->getResponse()->getContent();

        self::assertContains("error", $content);
        self::assertContains("Missing Required Fields", $content);
        self::assertEquals(200, $statusCode);
    }
}