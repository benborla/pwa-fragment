<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MonitorControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testPingWorks()
    {
        $this->client->request('GET', '/ping');
        $response = $this->client->getResponse();
        $expectedStatusCode = Response::HTTP_OK;
        $expectedStatusText = Response::$statusTexts[Response::HTTP_OK];

        $this->assertEquals($expectedStatusText, $response->getContent());
        $this->assertStringContainsString('text/plain', $response->headers->get('content-type'));
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
    }
}
