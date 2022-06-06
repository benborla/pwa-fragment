<?php

namespace App\Tests\DataSource;

use App\DataSource\Example;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testValidJSONResponse()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], '{
                "origin": "testorigin",
                "headers": {
                    "Host": "testhost"
                }
            }')
        ]);

        $handler = HandlerStack::create($mockHandler);
        $example = new Example(new Client(['handler' => $handler]));
        $info = $example->getExampleInfo();

        $this->assertContains('testorigin', $info);
        $this->assertContains('testhost', $info);
    }

    public function testGuzzleException()
    {
        $testRequest = new Request('GET', 'test');

        $mockHandler = new MockHandler([
            new RequestException("Error Communicating with Server", $testRequest)
        ]);

        $handler = HandlerStack::create($mockHandler);
        $example = new Example(new Client(['handler' => $handler]));
        $info = $example->getExampleInfo();

        $this->assertContains('', $info);
        $this->assertArrayHasKey('origin', $info);
    }
}
