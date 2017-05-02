<?php

namespace bigpaulie\CacheBundle\Doctrine\Support;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

trait Cacheable
{

    /**
     * Finds an Entity by its identifier.
     *
     * @param int $id
     * @param int|null $lifetime
     * @param \Closure|null $callable
     * @return \Closure|object|null
     * @throws NoResultException
     */
    public function find($id, $lifetime = null, \Closure $callable = null)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select($this->_entityName)
            ->from($this->_entityName, $this->_entityName);
        $queryBuilder->where($this->_entityName . ".id = :id")->setParameter(':id', $id);
        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();

        /**
         * If a lifetime is specified than we can cache
         * otherwise skip caching.
         */
        if ( $lifetime !== null ) {
            $cacheID = $this->cacheId($this->_entityName, $id);
            $query->useResultCache(true, $lifetime, $cacheID);
        }

        /**
         * If no result is found and $callable is not null
         * that we can call the $callable at the end of the query
         * $callable will act as a default return for the method.
         */
        if ( $callable !== null ) {
            $result = null;
            try {

                $result = $query->getSingleResult(Query::HYDRATE_OBJECT);
            } catch (NoResultException $exception) {}

            return $result ? $result : $callable();
        }

        return $query->getSingleResult(Query::HYDRATE_OBJECT);
    }

    /**
     * Finds a single entity by a set of criteria.
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $lifetime
     * @param \Closure|null $callable
     * @return object|null
     * @throws NoResultException
     */
    public function findOneBy(array $criteria, array $orderBy = null, $lifetime = null, \Closure $callable = null)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select($this->_entityName)
            ->from($this->_entityName, $this->_entityName);

        foreach ($criteria as $field => $value) {
            $queryBuilder->andWhere($this->_entityName . ".{$field} = :{$field}")
                ->setParameter($field, $value);
        }

        if ( is_array($orderBy) ) {
            foreach ($orderBy as $field => $direction) {
                $queryBuilder->addOrderBy($field, $direction);
            }
        }

        $queryBuilder->setMaxResults(1);

        $query = $queryBuilder->getQuery();

        /**
         * If a lifetime is specified than we can cache
         * otherwise skip caching.
         */
        if ( $lifetime ) {
            $cacheID = $this->cacheId($this->_entityName, serialize($criteria));
            $query->useResultCache(true, $lifetime, $cacheID);
        }

        /**
         * If no result is found and $callable is not null
         * that we can call the $callable at the end of the query
         * $callable will act as a default return for the method.
         */
        if ( $callable !== null ) {
            $result = null;
            try {

                $result = $query->getSingleResult(Query::HYDRATE_OBJECT);
            } catch (NoResultException $exception) {}

            return $result ? $result : $callable();
        }

        return $query->getSingleResult(Query::HYDRATE_OBJECT);
    }

    /**
     * Finds all entities in the repository.
     *
     * @param null $lifetime
     * @param \Closure|null $callable
     * @return mixed|null
     * @throws NoResultException
     */
    public function findAll($lifetime = null, \Closure $callable = null)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select($this->_entityName)
            ->from($this->_entityName, $this->_entityName);

        $query = $queryBuilder->getQuery();

        /**
         * If a lifetime is specified than we can cache
         * otherwise skip caching.
         */
        if ( $lifetime !== null ) {
            $cacheID = $this->cacheId($this->_entityName);
            $query->useResultCache(true, $lifetime, $cacheID);
        }

        /**
         * If no result is found and $callable is not null
         * that we can call the $callable at the end of the query
         * $callable will act as a default return for the method.
         */
        if ( $callable !== null ) {
            $result = null;
            try {

                $result = $query->getResult(Query::HYDRATE_OBJECT);
            } catch (NoResultException $exception) {}

            return $result ? $result : $callable();
        }

        return $query->getResult(Query::HYDRATE_OBJECT);
    }

    /**
     * Generate a cache id from a predefined key and a unique salt.
     *
     * @param string $key
     * @param string $unique
     * @return string
     */
    protected function cacheId($key, $unique = "")
    {
        return md5($key . $unique);
    }
}