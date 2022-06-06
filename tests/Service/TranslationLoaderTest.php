<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TranslationLoaderTest extends WebTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testShouldConstruct()
    {
        $fixturesDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR;
        $locale = 'en';
        $domain = 'default';
        $file = "${fixturesDir}${domain}.${locale}.tryml";

        $fileLoader = new \Symfony\Component\Translation\Loader\YamlFileLoader($file);
        $translationLoader = new \App\Service\TranslationLoader($fileLoader);

        $this->assertInstanceOf('App\Service\TranslationLoader', $translationLoader);
    }

    public function testLoadWithDefaultDomain()
    {
        $fixturesDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR;
        $locale = 'en';
        $domain = 'default';
        $file = "${fixturesDir}${domain}.${locale}.tryml";

        $loader = self::$container->get('App\Service\TranslationLoader');
        $catalogue = $loader->load($file, $locale, $domain);
        $translated = $catalogue->get('test.translation', $domain);

        $this->assertEquals('Default test translation', $translated);
    }

    public function testLoadWithOverriddenDomain()
    {

        $fixturesDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR;
        $locale = 'en';
        $domain = 'override';
        $file = "${fixturesDir}${domain}.${locale}.tryml";

        $loader = self::$container->get('App\Service\TranslationLoader');
        $catalogue = $loader->load($file, $locale, $domain);
        $translated = $catalogue->get('test.translation', $domain);

        $this->assertEquals('Overridden translation', $translated);
    }

    public function testLoadWithMissingOverridenId()
    {

        $fixturesDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR;
        $locale = 'en';
        $domain = 'override';
        $file = "${fixturesDir}${domain}.${locale}.tryml";

        $loader = self::$container->get('App\Service\TranslationLoader');
        $catalogue = $loader->load($file, $locale, $domain);
        $translated = $catalogue->get('test.other-translation', $domain);

        $this->assertEquals('Default other translation', $translated);
    }

    public function testLoadWithNonDefaultLocale()
    {
        $fixturesDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR;
        $locale = 'sv';
        $domain = 'default';
        $file = "${fixturesDir}${domain}.${locale}.tryml";

        $loader = self::$container->get('App\Service\TranslationLoader');
        $catalogue = $loader->load($file, $locale, $domain);
        $translated = $catalogue->get('test.translation', $domain);

        $this->assertEquals('Testets grundöversättning', $translated);
    }
}
