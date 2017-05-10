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
        /**
         * Check if the script is being called by the server of in cli
         * if the caller is "cli" than there is no need to setup the namespace.
         *
         * Also if the caller is "cli" there is no $_SERVER['SERVER_NAME'] global.
         */
        if ( php_sapi_name() != PHP_SAPI ) {
            $server = ($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
            $namespace = str_replace('.', '_', $server);
            $this->setNamespace($namespace);
        }
    }
}