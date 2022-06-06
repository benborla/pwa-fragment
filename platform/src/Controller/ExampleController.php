<?php

/**
 * This example controller and its files can be removed when creating
 * a new fragment server. The files ready for removal are:
 * - ./src/Controller/ExampleController.php
 * - ./src/DataSource/Example.php
 * - ./template/example.html.twig
 * - ./template/example.translation.html.twig
 */

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExampleController extends AbstractController
{
    /**
     * When doing AJAX-requests in non local-test mode
     * the path should be prefixed for Nginx.
     *
     * @var string
     */
    public const BASE_URL = '/fragment/example';

    /**
     * @Route("/sample")
     */
    public function pageMain(Request $request)
    {
        $siteId = $request->query->get('siteid') ? : 0;
        $brandCode = $request->query->get('brandcode');
        //return $this->render('example.nav.html.twig');
        return $this->render('favicon.html.twig', [
          'siteId' =>  $siteId,
          'brandCode' => $brandCode,
        ]);
    }

    /**
     * @Route("/{_locale}/translation")
     */
    public function pageWithTranslation(Request $request)
    {
        $domain = $request->query->get('site_domain') ?: 'funcasino';
        $localTest = $request->query->get('local_test') === "1";

        return $this->render('example.translation.html.twig', [
            'site_domain' => $domain,
            'name' => 'Vera',
            'local_test' => $localTest
        ]);
    }

    /**
     * @Route("/data")
     */
    public function pageWithData(Request $request, \App\DataSource\Example $example)
    {
        $data = $example->getExampleInfo();
        $localTest = $request->query->get('local_test') === "1";

        return $this->render('example.html.twig', [
            'host' => $data['host'],
            'origin' => $data['origin'],
            'local_test' => $localTest,
        ]);
    }

    /**
     * @Route("/color-shower")
     */
    public function pageWithColor(Request $request, JWTEncoderInterface $encoder)
    {
        $localTest = $request->query->get('local_test') === "1";

        return $this->render('example.colorshower.html.twig', [
            'local_test' => $localTest,
            'token' => $encoder->encode([
                'username' => $request->query->get('playertoken', 'anon123')
            ]),
        ]);
    }

    /**
     * @Route("/eightball")
     */
    public function pageEightball(Request $request, JWTEncoderInterface $encoder)
    {
        $localTest = $request->query->get('local_test') === "1";

        $response = $this->render('example.eightball.html.twig', [
            'local_test' => $localTest,
            'token' => $encoder->encode([
                'username' => $request->query->get('playertoken', 'anon123')
            ]),
            'baseUrl' => $localTest ? '' : self::BASE_URL
        ]);

        // Adding a 'public' flag allows Nginx to cache the response.
        $response->headers->set('Cache-Control', 'max-age=10, public');

        return $response;
    }

    /**
     * @Route("/form-example")
     */
    public function pageFormExample(Request $request, TranslatorInterface $translator, JWTEncoderInterface $encoder)
    {
        $domain = $request->query->get('domain', 'default');
        $localTest = $request->query->get('local_test') === "1";
        $all = $translator->getCatalogue()->all($domain);
        $formTranslations = [];

        foreach ($all as $key => $value) {
            if (strpos($key, 'example.form') === 0) {
                $formTranslations[str_replace('example.form.', '', $key)] = $value;
            }
        }

        return $this->render('example.form.html.twig', [
            'local_test' => $localTest,
            'domain' => $domain,
            'translations' => $formTranslations,
            'token' => $encoder->encode([
                'username' => $request->query->get('playertoken', 'anon123')
            ]),
            'baseUrl' => $localTest ? '' : self::BASE_URL
        ]);
    }

    /**
     * @Route("/code-split")
     */
    public function pageCodeSplitExample(
        Request $request,
        TranslatorInterface $translator,
        JWTEncoderInterface $encoder
    ) {
        $domain = $request->query->get('domain', 'base');
        $localTest = $request->query->get('local_test') === "1";

        return $this->render('example.code-split.html.twig', [
            'local_test' => $localTest,
            'domain' => $domain,
            'token' => $encoder->encode([
                'username' => $request->query->get('playertoken', 'anon123')
            ]),
            'baseUrl' => $localTest ? '' : self::BASE_URL
        ]);
    }

    /**
     * @Route(
     *   "/log/{level}",
     *   defaults={"level": "debug"},
     *   requirements={"level": "debug|info|notice|warning|error|critical|alert|emergency"}
     * )
     *
     */
    public function log($level, LoggerInterface $logger)
    {
        $logMessageFormat = '%s log message.';
        $logMessage = sprintf($logMessageFormat, ucfirst($level));

        if ($level === 'info') {
            $logger->info($logMessage);
        } elseif ($level === 'notice') {
            $logger->notice($logMessage);
        } elseif ($level === 'warning') {
            $logger->warning($logMessage);
        } elseif ($level === 'error') {
            $logger->error($logMessage);
        } elseif ($level === 'critical') {
            $logger->critical($logMessage);
        } elseif ($level === 'alert') {
            $logger->alert($logMessage);
        } elseif ($level === 'emergency') {
            $logger->emergency($logMessage);
        } else {
            $logger->debug($logMessage);
        }


        return new Response(sprintf('%s-level message was logged 📜', strtoupper($level)));
    }

    /**
     * @Route("/info")
     */
    public function pageInfo()
    {
        ob_start();
        phpinfo();
        $phpInfo = ob_get_clean();
        return new Response($phpInfo);
    }
}
