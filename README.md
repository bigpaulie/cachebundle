# CacheBundle [![Build Status](https://travis-ci.org/bigpaulie/cachebundle.svg?branch=master)](https://travis-ci.org/bigpaulie/cachebundle)
This bundle provides some extra functionality to the cache layer by exposing a service and a trait in order to make it easier for you to cache your queries.

At the moment this bundle works with Symfony 2.8.x PHP 5.6, PHP 7 and MySQL/MariaDB 
## Installation
The preferred way to install the bundle is via composer
```
composer require bigpaulie/symfony-cachebundle "dev-master" --prefer-dist
```

After requiring the package you should add the bundle to your bundle array in the AppKernel.php
```php
$bundles = array(
        ...
        new bigpaulie\CacheBundle\BigpaulieCacheBundle(),
);
```

Import the services.yml in your config.yml at the top of the file
```yml
- { resource: "@BigpaulieCacheBundle/Resources/config/services.yml"}
```

Add a new parameter in parameters.yml
```yml
memcached_servers:
        - { host: 127.0.0.1, port: 11211 }
```

Enable/disable caching for specific environments

Place the following in your config_*.yml file
```yml
    bigpaulie_cache:
        enable: true|false
```

Tell doctrine to use a given cache driver
```yml
doctrine:
    orm:
        metadata_cache_driver:
            type: service
            id: doctrine.cache.driver.memcached
        query_cache_driver:
            type: service
            id: doctrine.cache.driver.memcached
        result_cache_driver:
            type: service
            id: doctrine.cache.driver.memcached
```

## Query Caching
The bundle follows the official API as described in the documentation at [Doctrine Project](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/caching.html)
#### DQL Caching


```php
// $qb instanceof QueryBuilder

$qb->select('u')
   ->from('User', 'u')
   ->where('u.id = ?1')
   ->orderBy('u.name', 'ASC')
   ->setParameter(1, 100);
   
$query = $qb->getQuery();
$query->setQueryCaching(true);

$result = $query->getResult();
```

The setQueryCaching() method supports two additional parameters: lifetime and key.
```php
$query->setQueryCaching(true, 3600, 'my_unique_key');
```

#### Entity Caching
Symfony Framework doesn't support entity caching out of the box, here enters the Cacheable trait.
Use the Cacheable trait in the repositories you want to cache.

```php
use bigpaulie\CacheBundle\Doctrine\Support\Cacheable;
```

##### Overridden methods:
#### find()
```php
 find($id, $lifetime = null, \Closure $callable = null)
```
If you specify a lifetime for your cache than the result will be cached.

Additionally there is a third parameter to which you can pass an Closure which will act like a default return in case the query doesn't return any result.

#### findOneBy()
```php
findOneBy(array $criteria, array $orderBy = null, $lifetime = null, \Closure $callable = null)
```

If you specify a lifetime for your cache than the result will be cached.

Additionally you can also pass a Closure as the forth parameter which will act as a default return if the query doesn't return any results.

#### findAll()
```php
findAll($lifetime = null, \Closure $callable = null)
```

If you specify a lifetime for your cache than the result will be cached.

Additionally you can also pass a Closure as the second parameter which will act as a default return if the query doesn't return any results.

##### Example Usage
```php
use bigpaulie\CacheBundle\Doctrine\Support\Cacheable;
use Doctrine\ORM\EntityRepository;

/**
 * SomeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SomeRepository extends EntityRepository
{
    use Cacheable;
}
```

```php
// No caching
$this->getDoctrine()->getRepository('SomeBundle:SomeEntity')->find(1);

// Caching for 3600 seconds
$this->getDoctrine()->getRepository('SomeBundle:SomeEntity')->find(1, 3600);

// Caching for 3600 seconds and passing a Closure
$this->getDoctrine()->getRepository('SomeBundle:SomeEntity')->find(1, 3600, function () {
    return new NullObject();
});
```

#### Contribution
Feel free to contribute to this project, together we can make it a better project. 

Fork, code, submit a pull request or open a issue.