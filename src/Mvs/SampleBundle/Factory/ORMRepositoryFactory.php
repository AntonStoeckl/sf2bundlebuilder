<?php

namespace Mvs\SampleBundle\Factory;

use Doctrine\Common\Util\Debug;

class ORMRepositoryFactory
{
    protected $orm;

    protected $repoRegistry = array();

    public function __construct($orm)
    {
        $this->orm = $orm;
    }

    public function getRepository($repoName)
    {
        if (!array_key_exists($repoName, $this->repoRegistry)) {
            $this->repoRegistry[$repoName] = $this->orm->getRepository($repoName);
        }

        return $this->repoRegistry[$repoName];
    }
}