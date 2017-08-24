<?php
/*
 * This file is part of the francescotrucchia/soisy package.
 *
 * (c) Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayExtensions\CommandHandling;

use Broadway\CommandHandling\CommandHandler;
use BroadwayExtensions\EventSourcing\Repository;

class ReflectorCommandHandler implements CommandHandler
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed $command
     */
    public function handle($command)
    {
        $reflection = new \ReflectionObject($command);
        $method = lcfirst($reflection->getShortName());

        $aggregateReflection = new \ReflectionClass($this->repository->getType());
        if ($aggregateReflection->hasMethod($method)) {
            $method = $aggregateReflection->getMethod($method);

            if ($method->isStatic()) {
                $aggregate = call_user_func_array($this->repository->getType().'::'.$method->getShortName(), [$command]);
            }

            $this->repository->save($aggregate);
        }

        if (method_exists($this->repository, $method)) {
            var_dump('passo di qua');
        }
    }
}
