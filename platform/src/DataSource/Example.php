<?php

namespace App\DataSource;

use GuzzleHttp\Exception\RequestException;

class Example
{
    /**
     * @var GuzzleHttp\ClientInterface
     */
    private $client;

    public function __construct(\GuzzleHttp\ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getExampleInfo()
    {
        try {
            $res = json_decode($this->client->request('GET', 'http://httpbin.org/get')->getBody(), true);
        } catch (RequestException $err) {
            return [
                'origin' => '',
                'host' => ''
            ];
        }

        return [
            'origin' => $res['origin'],
            'host' => $res['headers']['Host']
        ];
    }
}
