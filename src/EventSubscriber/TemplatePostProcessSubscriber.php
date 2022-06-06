<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\DomCrawler\Crawler;

class TemplatePostProcessSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', -2048]
        ];
    }

    public function onKernelResponse(KernelEvent $event)
    {
        $isComponent = $event->getRequest()->attributes->get('_route') === 'components';

        if (! $isComponent) {
            return;
        }

        $response = (string) $event->getResponse()->getContent();
        $crawler = new Crawler($response);
        $getContent = $crawler->filter("#pwa-app")->html();
    }
}
