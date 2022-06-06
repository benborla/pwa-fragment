<?php

/**
 * This is an example file and can be removed
 */

namespace App\DataSource;

use GuzzleHttp\Exception\RequestException;

class Question
{
    private $client;

    public function __construct(\GuzzleHttp\ClientInterface $client)
    {
        $this->client = $client;
    }

    public static function identifyQuestion(string $questionCandidate)
    {
        $questionCandidate = trim($questionCandidate);

        return mb_substr($questionCandidate, -1, 1) === '?';
    }

    public function answerQuestion(string $question)
    {
        try {
            $body = $this->client->request(
                'GET',
                'https://yesno.wtf/api'
            )->getBody();
            $response = json_decode($body, true);
        } catch (RequestException $err) {
            return 'example.form.answer.no_connection';
        }

        return 'example.form.answer.' . $response['answer'];
    }
}
