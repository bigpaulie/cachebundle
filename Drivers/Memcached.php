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
        // TODO: Implement parseNamespace() method.
    }
}