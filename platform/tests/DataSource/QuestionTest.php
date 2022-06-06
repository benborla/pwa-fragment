<?php

namespace App\Tests\DataSource;

use App\DataSource\Question;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    public function testValidQuestion()
    {
        $this->assertTrue(Question::identifyQuestion('Question?'));
    }

    public function testValidQuestionWithTrailingWhitespace()
    {
        $this->assertTrue(Question::identifyQuestion('Question? '));
    }

    public function testInvalidQuestion()
    {
        $this->assertFalse(Question::identifyQuestion('Question.'));
    }

    public function testEmptyQuestion()
    {
        $this->assertFalse(Question::identifyQuestion(''));
    }

    public function testAnswerQuestionWithYes()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], '{
                "answer": "yes"
            }')
        ]);

        $handler = new HandlerStack($mockHandler);
        $question = new Question(new Client(['handler' => $handler]));
        $answer = $question->answerQuestion('Question?');

        $this->assertEquals('example.form.answer.yes', $answer);
    }

    public function testAnswerQuestionWithGuzzleException()
    {
        $testRequest = new Request('GET', 'test');

        $mockHandler = new MockHandler([
            new RequestException("Error Communicating with Server", $testRequest)
        ]);

        $handler = new HandlerStack($mockHandler);
        $question = new Question(new Client(['handler' => $handler]));
        $answer = $question->answerQuestion('Question?');

        $this->assertEquals('example.form.answer.no_connection', $answer);
    }
}
