<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\DataSource\Example;

class MonitorController extends AbstractController
{
    /**
     * Basic fragment health check
     *
     * @Route("/ping")
     */
    public function ping()
    {
        return new Response(
            Response::$statusTexts[Response::HTTP_OK],
            Response::HTTP_OK,
            [
                'content-type' => 'text/plain'
            ]
        );
    }
}
