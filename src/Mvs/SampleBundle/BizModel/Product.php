<?php

namespace Mvs\SampleBundle\BizModel;

use Mvs\SampleBundle\Repository\ProductRepositoryInterface;

class Product
{
    /** @var \Mvs\SampleBundle\Repository\ProductRepository */
    protected $productRepository;

    /**
     * The constructor.
     *
     * @param ProductRepositoryInterface $repository
     */
    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->productRepository = $repository;
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
        return $this->productRepository->findAllOrderedByName();
    }

    /**
     * @param int|string $id
     * @return object
     */
    public function findOne($id)
    {
        return $this->productRepository->findOneById($id);
    }
}