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
     * @param StorageInterface $cache
     */
    public function __construct(ProductRepositoryInterface $repository, StorageInterface $cache)
    {
        $this->productRepository = $repository;
        $this->cache = $cache;
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