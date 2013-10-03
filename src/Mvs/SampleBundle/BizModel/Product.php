<?php

namespace Mvs\SampleBundle\BizModel;

use Mvs\SampleBundle\Repository\ProductRepositoryInterface;
use Mvs\SampleBundle\Repository\ProductRepository;
use Mvs\SampleBundle\Entity\Product as ProductEntity;
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
        /** @var ProductEntity $product */
        $product = $this->productRepository->createOne($data);

        if (is_object($product)) {
            $this->cache->setItem($product->getId(), $product);
            $this->cache->replaceItem('_all_', $this->productRepository->findAllOrderedByName());
        }

        return $product;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $data = $this->cache->getItem('_all_', $success);

        if (! $success) {
            $data = $this->productRepository->findAllOrderedByName();
            $this->cache->setItem('_all_', $data);
        }

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