<?php

namespace bigpaulie\CacheBundle\Contracts;

/**
 * Interface CacheDriver
 * All Cache drivers must implement this interface
 *
 * @package bigpaulie\CacheBundle\Contracts
 */
interface CacheDriver
{

    /**
     * It is recommended that in a multi-domain application
     * you should use your domain name as a namespace for your cache.
     */
    public function parseNamespace();
}