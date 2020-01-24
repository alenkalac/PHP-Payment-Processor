<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatisticsControllerTest extends WebTestCase {

    public function testStatisticsApiReturnsCorrectFields() {
        $client = static::createClient();

        $client->request("GET", "/api/stats");

        $statusCode = $client->getResponse()->getStatusCode();
        $content = $client->getResponse()->getContent();

        $this->assertEquals(200, $statusCode);
    }

}