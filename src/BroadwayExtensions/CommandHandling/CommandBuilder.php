<?php

/*
 * This file is part of the francescotrucchia/broadway-extensions package.
 *
 * (c) Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayExtensions\CommandHandling;

/**
 * Class CommandBuilder
 * @package Soisy\Cqrs\CommandHandling
 */
class CommandBuilder
{
    protected $classesMap = [];

    /**
     * CommandBuilder constructor.
     *
     * @param array $classesMap
     */
    public function __construct(array $classesMap = [])
    {
        $this->classesMap = $classesMap;
    }

    /**
     * @param array $classeMap
     */
    public function setClassesMap(array $classeMap)
    {
        $this->classesMap = $classeMap;
    }

    /**
     * @param $className
     * @param $params
     * @return object
     */
    public function build($className, $params)
    {
        $class      = new \ReflectionClass($className);
        $parameters = $class->getConstructor()->getParameters();

        $args = [];
        foreach ($params as $index => $param) {
            if (!isset($parameters[$index])) {
                throw new \RangeException('Invalid parameters with offset ' . $index);
            }
            if ($parameters[$index]->getClass()) {
                $className = $parameters[$index]->getClass()->getName();
                if (isset($this->classesMap[$className])) {
                    $className = $this->classesMap[$className];
                }

                if (is_callable($className)) {
                    $args[] = $className($param);
                } else {
                    $args[] = new $className($param);
                }
            } else {
                $args[] = $param;
            }
        }

        return $class->newInstanceArgs($args);
    }
}
