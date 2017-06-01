<?php
/**
 * Created by PhpStorm.
 * User: paul
 * Date: 01.06.2017
 * Time: 19:35
 */

namespace bigpaulie\CacheBundle\Doctrine\Common\Cache;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MemcachedCache extends \Doctrine\Common\Cache\MemcachedCache implements ContainerAwareInterface
{

    /**
     * The service container.
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * Set the service container.
     *
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Check if the enable parameter is present
     * and if the caching is enabled.
     *
     * @return null|bool
     */
    protected function isEnabled()
    {
        if ( getenv('TESTING') == "UNIT" ) {
            return true;
        } else if ($this->container) {
            $params = $this->container->getParameter('bigpaulie_cache');
            return (boolean)$params['enable'];
        }
        return false;
    }

    /**
     * Get item.
     *
     * @param $id
     * @return bool|mixed
     */
    public function get($id)
    {
        if ( $this->isEnabled() ) {
            return parent::get($id);
        }
        return false;
    }

    public function contains($id)
    {
        if ( $this->isEnabled() ) {
            return parent::contains($id);
        }
        return false;
    }

    public function save($id, $data, $lifetime = 0)
    {
        if ( $this->isEnabled() ) {
            return parent::save($id, $data, $lifetime);
        }
        return false;
    }
}