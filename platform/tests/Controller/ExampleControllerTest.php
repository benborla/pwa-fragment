<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExampleControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testPageMainRoute()
    {
        $crawler = $this->client->request('GET', '/');
        $response = $this->client->getResponse();
        $linkCount = $crawler->filter('body a')->count();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(8, $linkCount);
    }

    public function testPageWorld()
    {
        $crawler = $this->client->request('GET', '/world');
        $response = $this->client->getResponse();
        $foundHello = $crawler->filter('body:contains("Hello World")')->count();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $foundHello);
    }

    public function testPageWithTranslationDefaultEnglish()
    {
        $crawler = $this->client->request('GET', '/en/translation?site_domain=default');
        $response = $this->client->getResponse();
        $foundTranslation = $crawler->filter('body h1:contains("Default English translation")')->count();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $foundTranslation);
    }

    public function testPageWithTranslationDefaultSwedish()
    {
        $crawler = $this->client->request('GET', '/sv/translation?site_domain=default');
        $response = $this->client->getResponse();
        $foundTranslation = $crawler->filter('body h1:contains("Svensk grundöversättning")')->count();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $foundTranslation);
    }

    public function testPageWithTranslationOverrideEnglish()
    {
        $crawler = $this->client->request('GET', '/en/translation');
        $response = $this->client->getResponse();
        $foundTranslation = $crawler->filter('body h1:contains("Fun Casino translation")')->count();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $foundTranslation);
    }

    public function testPageWithTranslationOverrideSwedish()
    {
        $crawler = $this->client->request('GET', '/sv/translation');
        $response = $this->client->getResponse();
        $foundTranslation = $crawler->filter('body h1:contains("Det Roliga Kasinot\'s översättning")')->count();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $foundTranslation);
    }

    public function testPageWithTranslationMissingOverrideSwedish()
    {
        $crawler = $this->client->request('GET', '/sv/translation');
        $response = $this->client->getResponse();
        $foundTranslation = $crawler->filter('body h1:contains("Hej Vera, från default-domänen")')->count();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $foundTranslation);
    }

    public function testPageWithData()
    {
        $mockExample = $this->createMock('App\DataSource\Example');
        $mockExample->method('getExampleInfo')->willReturn([
            'origin' => '1.1.1.1',
            'host' => 'example.com'
        ]);

        self::$container->set('App\DataSource\Example', $mockExample);
        $this->client->request('GET', '/data');

        $content = $this->client->getResponse();
        $originExists = strpos($content, 'Requested by: <code>1.1.1.1</code>') !== false;
        $hostExists = strpos($content, 'Returned by: <code>example.com</code>') !== false;

        $this->assertTrue($originExists);
        $this->assertTrue($hostExists);
    }

    public function testPageWithDataEmpty()
    {
        $mockExample = $this->createMock('App\DataSource\Example');
        $mockExample->method('getExampleInfo')->willReturn([
            'origin' => '',
            'host' => ''
        ]);

        self::$container->set('App\DataSource\Example', $mockExample);
        $this->client->request('GET', '/data');

        $content = $this->client->getResponse();

        $originExists = strpos($content, 'Requested by: <code></code>') !== false;
        $hostExists = strpos($content, 'Returned by: <code></code>') !== false;

        $this->assertTrue($originExists);
        $this->assertTrue($hostExists);
    }

    public function testPageWithColor()
    {
        $crawler = $this->client->request('GET', '/color-shower');
        $foundVueWrapper = $crawler->filter('.color-shower-wrapper')->count();
        $nonExistingLocalTestMarkup = $crawler->filter('meta[http-equiv="Authorization"]')->count();

        $this->assertEquals(1, $foundVueWrapper);
        $this->assertEquals(0, $nonExistingLocalTestMarkup);
    }

    public function testPageWithColorLocalTest()
    {
        $crawler = $this->client->request('GET', '/color-shower?local_test=1');
        $foundVueWrapper = $crawler->filter('.color-shower-wrapper')->count();
        $nonExistingLocalTestMarkup = $crawler->filter('meta[http-equiv="Authorization"]')->count();

        $this->assertEquals(1, $foundVueWrapper);
        $this->assertEquals(1, $nonExistingLocalTestMarkup);
    }

    public function testPageEightball()
    {
        $crawler = $this->client->request('GET', '/eightball');
        $foundVueWrapper = $crawler->filter('.eightball-wrapper')->count();
        $baseUrl = $crawler->filter('eightball')->attr('base-url');

        $this->assertEquals('/fragment/boilerplate', $baseUrl);
        $this->assertEquals(1, $foundVueWrapper);
    }

    public function testPageEightballLocalTest()
    {
        $crawler = $this->client->request('GET', '/eightball?local_test=1');
        $foundVueWrapper = $crawler->filter('.eightball-wrapper')->count();
        $nonExistingLocalTestMarkup = $crawler->filter('meta[http-equiv="Authorization"]')->count();
        $baseUrl = $crawler->filter('eightball')->attr('base-url');

        $this->assertEquals('', $baseUrl);
        $this->assertEquals(1, $foundVueWrapper);
        $this->assertEquals(1, $nonExistingLocalTestMarkup);
    }

    public function testPageFormExample()
    {
        $crawler = $this->client->request('GET', '/form-example');
        $vueWrapper = $crawler->filter('.form-example-wrapper');
        $markup = $vueWrapper->html();
        $translationExists = strpos($markup, '"questionLabel":"Your question"') !== false;

        $this->assertEquals(1, $vueWrapper->count());
        $this->assertTrue($translationExists);
    }

    public function testPageCodeSplitExample()
    {
        $crawler = $this->client->request('GET', '/code-split');
        $vueWrapper = $crawler->filter('.code-split-wrapper');
        $this->assertEquals(1, $vueWrapper->count());
    }

    public function testPageLogDefault()
    {
        $this->client->request('GET', '/log');
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertStringContainsString("DEBUG-level message", $content);
    }

    public function testPageLogNamedParameter()
    {
        $this->client->request('GET', '/log/warning');
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertStringContainsString("WARNING-level message", $content);
    }

    public function testPageLogBadlyNamedParameter()
    {
        $this->client->request('GET', '/log/hockeypuck');
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testPageInfo()
    {
        $crawler = $this->client->request('GET', '/info');
        $infoHTML = $crawler->filter('html')->html();

        $infoExists = strpos($infoHTML, 'PHP Version =') !== false;

        $this->assertTrue($infoExists);
    }
}
