<?php

/*
 * This file is part of the easelaboratory/broadway-extensions package.
 *
 * (c) easelab.it <os@easelab.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayExtensions\Messaging;

use Broadway\Domain\DateTime;
use Broadway\Serializer\Serializable;
use BroadwayExtensions\ValueObject\StaticValueObject;
use BroadwayExtensions\ValueObject\ValueObject;

/**
 * Abstract class FairyDomainEvent
 */
abstract class FairyDomainEvent implements Serializable
{
    const TYPE_EVENT = 'event';

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * FairyDomainEvent constructor.
     *
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $value = lcfirst(str_replace('get', '', $name));
        if (!isset($this->payload[$value])) {
            throw new \BadMethodCallException(sprintf('Call to undefined method %s::%s()', static::class, $name));
        }

        return $this->payload[$value];

    }

    /**
     * @return string
     */
    public function messageType()
    {
        return self::TYPE_EVENT;
    }

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        $reflectionClass = new \ReflectionClass(static::class);

        $args = [];
        $reflectionMethod = $reflectionClass->getConstructor();
        foreach ($reflectionMethod->getParameters() as $index => $param) {
            if(!isset($data[$param->getName()])) {
                throw new \UnexpectedValueException(sprintf('Value %s is not defined', $param->getName()));
            }
            $args[$index] = $data[$param->getName()];
            if ($param->getClass()) {
                $class = $param->getClass();

                if(in_array(StaticValueObject::class, array_keys($class->getInterfaces()))) {
                    $className = $class->getName();
                    $args[$index] = $className::create($data[$param->getName()]);
                } else {
                    if($class->getName() == \DateTimeInterface::class) {
                        $class = new \ReflectionClass(\DateTimeImmutable::class);
                    }
                    $args[$index] = $class->newInstance($data[$param->getName()]);
                }
            }
        }

        $object = $reflectionClass->newInstanceArgs($args);

        return $object;
    }

    /**
     * @return array
     * @throws \UnexpectedValueException
     */
    public function serialize()
    {
        $data = [];
        foreach ($this->payload as $key => $value) {
            $data[$key] = $value;
            if (is_object($value)) {
                if ($value instanceof ValueObject) {
                    $data[$key] = (string)$value;
                    continue;
                }
                if ($value instanceof \DateTimeInterface) {
                    $data[$key] = $value->format(DateTime::FORMAT_STRING);
                    continue;
                }
                throw new \UnexpectedValueException(sprintf('Impossible to serialize key %s', $key));
            }
        }
        return $data;
    }
}
