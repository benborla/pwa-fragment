<?php

namespace App\Service;

use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogueInterface;

class TranslationLoader implements LoaderInterface
{
    public const DEFAULT_DOMAIN = 'default';

    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function load($resource, $locale, $domain = 'messages'): MessageCatalogueInterface
    {
        $catalogue = $this->loader->load($resource, $locale, $domain);

        if ($domain === self::DEFAULT_DOMAIN) {
            return $catalogue;
        }

        $baseResource = str_replace("$domain.$locale", self::DEFAULT_DOMAIN . '.' . $locale, $resource);

        $baseCatalogue = $this->loader->load($baseResource, $locale, $domain);
        $baseCatalogue->add($catalogue->all($domain), $domain);

        return $baseCatalogue;
    }
}
