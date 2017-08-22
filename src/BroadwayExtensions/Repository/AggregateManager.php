<?php

/*
 * This file is part of the francescotrucchia/broadway-extensions package.
 *
 * (c) Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayExtensions\Repository;

use Broadway\ReadModel\Repository;

/**
 * Manager to register and retrieve aggregate repositories
 */
class AggregateManager
{
    /**
     * @var array
     */
    private $repositories = [];

    /**
     * @param $id
     * @param Repository $repository
     */
    public function registerRepository($id, Repository $repository)
    {
        $this->repositories[$id] = $repository;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getRepository($id)
    {
        if (!isset($this->repositories[$id])) {
            throw new \InvalidArgumentException(sprintf('Repository with id %s is not registered', $id));
        }

        return $this->repositories[$id];
    }
}
