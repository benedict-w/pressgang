<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\Common\Cache\ArrayCache;
use Twig\CacheExtension\CacheProvider\DoctrineCacheAdapter;
use Twig\CacheExtension\CacheStrategy\LifetimeCacheStrategy;
use Twig\CacheExtension\Extension as CacheExtension;

add_filter('get_twig', function($twig) {
    $cacheProvider  = new DoctrineCacheAdapter(new ArrayCache());
    $cacheStrategy  = new LifetimeCacheStrategy($cacheProvider);
    $cacheExtension = new CacheExtension($cacheStrategy);
    $twig->addExtension($cacheExtension);
    return $twig;
});