<?php

namespace Mvs\SampleBundle\BizModel;

use Mvs\SampleBundle\Repository\ProductRepositoryInterface;
use Mvs\SampleBundle\Repository\ProductRepository;
use Zend\Cache\StorageFactory;
use Zend\Cache\Storage\StorageInterface;

class Product
{
    /** @var ProductRepository */
    protected $productRepository;

    /** @var StorageInterface */
    protected $cache;

    /**
     * The constructor.
     *
     * @param ProductRepositoryInterface $repository
     */
    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->productRepository = $repository;

        $this->cache = StorageFactory::factory(array(
                'adapter' => array(
                    'name' => 'memcached',
                    'options' => array(
                        'servers' => array('localhost'),
                        'namespace' => 'db-table-product',
                        'ttl' => 30,
                    ),
                ),
                'plugins' => array(
                    // Don't throw exceptions on cache errors
                    'exception_handler' => array(
                        'throw_exceptions' => false
                    ),
                    'Serializer'
                )
            ));
    }

    /**
     * @param array $data
     * @return object
     */
    public function createOne(array $data)
    {
        return $this->productRepository->createOne($data);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $data = $this->productRepository->findAllOrderedByName();

        return $data;
    }

    /**
     * @param int|string $id
     * @return object
     */
    public function findOne($id)
    {
        $data = $this->cache->getItem($id, $success);

        if (! $success) {
            $data = $this->productRepository->findOneById($id);
            $this->cache->setItem($id, $data);
        }

        return $data;
    }
}