<?php

/*
 * This file is part of the easelaboratory/broadway-extensions package.
 *
 * (c) easelab.it <os@easelab.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayExtensions\Repository;

use Broadway\ReadModel\Repository;
use BroadwayExtensions\TestCase;

class AggregateManagerTest extends TestCase
{
    /**
     * @test
     */
    public function it_register_and_get_repository()
    {
        $repository = $this->prophesize(Repository::class);
        $am         = new AggregateManager();
        $am->registerRepository('app.repo.id', $repository->reveal());

        $this->assertEquals($repository->reveal(), $am->getRepository('app.repo.id'));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Repository with id app.repo.id is not registered
     */
    public function it_cant_get_not_registered_repository()
    {
        $am = new AggregateManager();
        $am->getRepository('app.repo.id');
    }
}
