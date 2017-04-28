<?php

use Mockery AS mock;

/**
 * Class MemcachedTest
 * Testing the Memcached cache driver
 *
 * @package Tests
 */
class MemcachedTest extends \PHPUnit\Framework\TestCase
{

    /**
     * The instance of the cache driver
     * @var \bigpaulie\CacheBundle\Drivers\Memcached
     */
    private $driver;

    /**
     * Preforming test setup
     */
    public function setup()
    {
        $this->driver = new \bigpaulie\CacheBundle\Drivers\Memcached();
    }

    /**
     * Testing the drivers save method
     * this test should pass
     */
    public function testDriverSaveMethodShouldReturnTrue()
    {
        $memcached = mock::mock(\Memcached::class);
        $memcached->shouldReceive('get');
        $memcached->shouldReceive('set')->andReturn(true);
        $this->driver->setMemcached($memcached);

        $this->assertTrue($this->driver->save('testid', 'some test data'));
    }

    /**
     * Testing the drivers get method
     * this test should pass
     */
    public function testDriverGetShouldReturnString()
    {
        $memcached = mock::mock(\Memcached::class);
        $memcached->shouldReceive('get')->andReturnValues(['some test data']);
        $this->driver->setMemcached($memcached);

        $this->assertEquals($this->driver->fetch('testid'), 'some test data');
    }

    /**
     * Testing the drivers contains method
     * this test should pass
     */
    public function testDriverContainsShouldReturnTrue()
    {
        $memcached = mock::mock(\Memcached::class);
        $memcached->shouldReceive('get')->andReturn(true);
        $this->driver->setMemcached($memcached);

        $this->assertTrue($this->driver->contains('testid'));
    }

    /**
     * Testing the drivers delete method
     * this test should pass
     */
    public function testDriverDeleteShouldReturTrue()
    {
        $memcached = mock::mock(\Memcached::class);
        $memcached->shouldReceive('get');
        $memcached->shouldReceive('delete')->andReturn(true);
        $this->driver->setMemcached($memcached);

        $this->assertTrue($this->driver->delete('testid'));
    }

    /**
     * Teardown the test
     */
    public function tearDown()
    {
        mock::close();
    }
}