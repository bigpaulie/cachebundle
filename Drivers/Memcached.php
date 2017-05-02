<?php

namespace bigpaulie\CacheBundle\Drivers;

use bigpaulie\CacheBundle\Contracts\CacheDriver;
use Doctrine\Common\Cache\MemcachedCache;

/**
 * Class Memcached
 * @package bigpaulie\CacheBundle\Drivers
 */
class Memcached extends MemcachedCache implements CacheDriver
{

    /**
     * It is recommended that in a multi-domain application
     * you should use your domain name as a namespace for your cache.
     */
    public function parseNamespace()
    {
        $server = ($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
        $namespace = str_replace('.', '_', $server);
        $this->setNamespace($namespace);
    }
}