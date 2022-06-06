<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use file_exists;
use file_get_contents;

class PwaController extends AbstractController
{
    /**
     * When doing AJAX-requests in non local-test mode
     * the path should be prefixed for Nginx.
     *
     * @var string
     */
    public const BASE_URL = '/fragment/pwa';

    /**
     * @Route("/")
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

    /**
     * This method returns the specified component of the specified brand
     *
     * @Route("/component", name="components")
     *
     * @var \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getComponent(Request $request)
    {
        $component = $request->query->get('component') ?: null;
        $brandCode = $request->query->get('brandcode') ?: null;

        if (is_null($component) || is_null($brandCode)) {
            throw new Exception('Unable to retrieve component');
        }

        return $this->render('components/loader.html.twig', [
            'component' => $component,
            'brandCode' => $brandCode,
            'meta' => [
                'brand' => $brandCode
            ]
        ]);
    }

    /**
     * This method returns the assets of PWA
     *
     * @Route("/assets", name="assets")
     *
     * @var \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAssets(Request $request)
    {
        $brandCode = $request->query->get('brandcode') ?: null;

        if (is_null($brandCode)) {
            throw new Exception('Unable to load assets');
        }

        return $this->render('assets.html.twig', [
            'brandCode' => $brandCode,
        ]);
    }

    /**
     * This method returns the manifest.json content of a specified brand
     *
     * @Route("/build/manifest.json", name="dynamic_manifest")
     *
     * @var \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getDynamicManifest(Request $request): JsonResponse
    {
        $brandCode = $request->query->get('brandcode') ? : null;

        if (is_null($brandCode)) {
            throw new \Exception('Unable to find manifest file');
        }

        $publicPath = $this->getParameter('kernel.project_dir') . "/public/build/$brandCode/manifest.json";
        $manifestIcons = [];

        $rawJson = \json_decode(\file_get_contents($publicPath), true);
        $icons = $rawJson['icons'] ?? null;

        if (! is_null($icons)) {
            $package = new Package(new JsonManifestVersionStrategy($publicPath));

            foreach ($icons as $icon) {
                $manifestIcons[] = [
                    'src' => $package->getUrl($icon['src']),
                    'sizes' => $icon['sizes'],
                    'type' => $icon['type']
                ];
            }
        }

        $rawJson['icons'] = $manifestIcons;

        return new JsonResponse($rawJson);
    }

    /**
     * This method returns the service-worker.js script
     *
     * @Route("/service-worker.js", name="service_worker")
     *
     * @var \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function serviceWorker(Request $request)
    {
        $sw = $this->getParameter('kernel.project_dir') . "/assets/sw.js";
        $fileContent = file_get_contents($sw);

        $response = new BinaryFileResponse($sw);
        $response->headers->set('Content-Type', 'text/javascript');

        return $response;
    }

    /**
     * This method returns the specified component of the specified brand
     *
     * @Route("/load-pwa", name="load_pwa")
     *
     * @var \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadPwa(Request $request)
    {
        $brandCode = $request->query->get('brandcode') ?: null;

        if (is_null($brandCode)) {
            throw new Exception('Unable to retrieve brand');
        }

        return $this->render('base.html.twig', [
            'brandCode' => $brandCode,
            'meta' => [
                'brand' => $brandCode
            ]
        ]);
    }
}
