<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class SecuredControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * Simulates a request with a previously obtained JWT.
     *
     * @return void
     */
    public function testRefreshToken()
    {
        $username = 'testuser';
        $jwtEncoder = self::$container->get('lexik_jwt_authentication.encoder');

        $token = $jwtEncoder->encode([
            'username' => $username
        ]);

        $this->client->request('GET', '/ajax/refresh-token', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer ${token}"
        ]);

        $isJsonResponse = $this->client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        );

        $responseContent = $this->client->getResponse()->getContent();
        $jsonResponseObject = json_decode($responseContent);

        $decodedToken = $jwtEncoder->decode($jsonResponseObject->token);

        $this->assertTrue($isJsonResponse);
        $this->assertEquals($username, $decodedToken['username']);
    }

    public function testRefreshTokenExpired()
    {
        $username = 'testuser';
        $jwtEncoder = self::$container->get('lexik_jwt_authentication.encoder');

        $token = $jwtEncoder->encode([
            'username' => $username,
            'exp' => time() - 500
        ]);

        $this->client->request('GET', '/ajax/refresh-token', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer ${token}"
        ]);

        $responseContent = $this->client->getResponse()->getContent();
        $jsonResponseObject = json_decode($responseContent);
        $this->assertObjectNotHasAttribute('token', $jsonResponseObject);
        $this->assertEquals('Expired JWT Token', $jsonResponseObject->message);
    }

    public function testRefreshTokenInvalid()
    {
        $token = 'INVALID_TOKEN';

        $this->client->request('GET', '/ajax/refresh-token', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer ${token}"
        ]);

        $isJsonResponse = $this->client->getResponse()->headers->contains(
            'Content-Type',
            'application/json'
        );

        $responseContent = $this->client->getResponse()->getContent();
        $jsonResponseObject = json_decode($responseContent);

        $this->assertTrue($isJsonResponse);
        $this->assertObjectNotHasAttribute('token', $jsonResponseObject);
        $this->assertEquals('Invalid JWT Token', $jsonResponseObject->message);
    }

    public function testRefreshTokenMissing()
    {
        $this->client->request('GET', '/ajax/refresh-token');

        $responseContent = $this->client->getResponse()->getContent();
        $jsonResponseObject = json_decode($responseContent);
        $this->assertObjectNotHasAttribute('token', $jsonResponseObject);
        $this->assertEquals('JWT Token not found', $jsonResponseObject->message);
    }

    public function testFormExampleReponseYes()
    {
        $controller = self::$container->get('App\Controller\SecuredController');
        $translator = self::$container->get('translator.default');
        $mockClient = $this->createMock('GuzzleHttp\Client');

        $mockQuestion = $this->getMockBuilder('App\DataSource\Question')
            ->setConstructorArgs([$mockClient])
            ->setMethods(['answerQuestion'])
            ->getMock();

        $mockQuestion->method('answerQuestion')->willReturn('example.form.answer.yes');

        $request = new Request();

        // Simulate a recieved POST request
        $request->initialize([], [
            'name' => 'Testname',
            'question' => 'Test question?'
        ]);

        $response = $controller->pageFormExampleResponse($request, $translator, $mockQuestion);
        $jsonResponseObject = json_decode($response->getContent());

        $this->assertEquals(1, $jsonResponseObject->status);
        $this->assertEquals('Yeah', $jsonResponseObject->answer);
    }

    public function testFormExampleReponseNo()
    {
        $controller = self::$container->get('App\Controller\SecuredController');
        $translator = self::$container->get('translator.default');
        $mockClient = $this->createMock('GuzzleHttp\Client');

        $mockQuestion = $this->getMockBuilder('App\DataSource\Question')
            ->setConstructorArgs([$mockClient])
            ->setMethods(['answerQuestion'])
            ->getMock();

        $mockQuestion->method('answerQuestion')->willReturn('example.form.answer.no');

        $request = new Request();

        // Simulate a recieved POST request
        $request->initialize([], [
            'name' => 'Testname',
            'question' => 'Test question?'
        ]);

        $response = $controller->pageFormExampleResponse($request, $translator, $mockQuestion);
        $jsonResponseObject = json_decode($response->getContent());

        $this->assertEquals(1, $jsonResponseObject->status);
        $this->assertEquals('Nope', $jsonResponseObject->answer);
    }

    public function testFormExampleReponseInvalid()
    {
        $controller = self::$container->get('App\Controller\SecuredController');
        $translator = self::$container->get('translator.default');
        $mockClient = $this->createMock('GuzzleHttp\Client');
        $realQuestion = new \App\DataSource\Question($mockClient);

        $request = new Request();

        // Simulate a recieved POST request
        $request->initialize([], [
            'name' => 'Testname',
            'question' => 'Test invalid question'
        ]);

        $response = $controller->pageFormExampleResponse($request, $translator, $realQuestion);
        $jsonResponseObject = json_decode($response->getContent());

        $this->assertEquals(0, $jsonResponseObject->status);
        $this->assertEquals('A question ends with a question mark, Testname.', $jsonResponseObject->error->message);
    }

    public function testPageEightballAnswer()
    {
        $controller = self::$container->get('App\Controller\SecuredController');
        $translator = self::$container->get('translator.default');
        $mockRandom = $this->createMock('App\DataSource\Random');

        $mockRandom->method('get')
            ->willReturn(19);

        $response = $controller->pageEightballAnswer(new Request(), $translator, $mockRandom);
        $jsonResponseObject = json_decode($response->getContent());


        $this->assertEquals('Very doubtful', $jsonResponseObject->answer);
    }
}
