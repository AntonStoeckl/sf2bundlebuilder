<?php

namespace Mvs\SampleBundle\Repository;

interface ProductRepositoryInterface
{
    /**
     * @return array
     */
    public function findAllOrderedByName();

    /**
     * @param int|string $id
     * @return object
     */
    public function findOneById($id);

    /**
     * @param array $data
     * @return object
     */
    public function createOne(array $data);
}