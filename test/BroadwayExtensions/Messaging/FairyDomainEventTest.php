<?php
/*
 * This file is part of the francescotrucchia/broadway-extensions package.
 *
 * (c) Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayExtensions\Messaging;

use Broadway\Domain\DateTime;
use BroadwayExtensions\TestCase;
use BroadwayExtensions\ValueObject\StaticValueObject;
use BroadwayExtensions\ValueObject\ValueObject;

class FairyDomainEventTest extends TestCase
{
    /**
     * @test
     */
    public function getter()
    {
        $date      = new \DateTimeImmutable();
        $testEvent = new TestEventFairy(new ValueObjectId('1'), StaticName::create('Francesco'), 'Trucchia', $date);
        $this->assertEquals(TestEventFairy::TYPE_EVENT, $testEvent->messageType());
        $this->assertEquals('1', $testEvent->getId());
        $this->assertEquals('1', $testEvent->id());
        $this->assertEquals(StaticName::create('Francesco'), $testEvent->getFirstName());
        $this->assertEquals('Trucchia', $testEvent->getLastname());
    }

    /**
     * @test
     */
    public function serialize()
    {
        $date      = new \DateTimeImmutable();
        $testEvent = new TestEventFairy(new ValueObjectId('1'), StaticName::create('Francesco'), 'Trucchia', $date);
        $this->assertEquals(json_encode([
            'id'          => '1',
            'firstName'   => 'Francesco',
            'lastname'    => 'Trucchia',
            'requestedAt' => $date->format(DateTime::FORMAT_STRING)
        ]), json_encode($testEvent->serialize()));
    }

    /**
     * @test
     */
    public function serializeWithoutDate()
    {
        $testEvent = new TestEventFairy(new ValueObjectId('1'), StaticName::create('Francesco'), 'Trucchia');
        $this->assertEquals([
            'id'          => '1',
            'firstName'   => 'Francesco',
            'lastname'    => 'Trucchia',
            'requestedAt' => null
        ], $testEvent->serialize());
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     */
    public function serializeWithInvalidEvent()
    {
        $testEvent = new InvalidEventFairy(new InvalidId('1'));
        $testEvent->serialize();
    }

    /**
     * @test
     */
    public function deserialize()
    {
        $date  = new \DateTimeImmutable();
        $event = TestEventFairy::deserialize([
            'id'          => '1',
            'firstName'   => 'Francesco',
            'lastname'    => 'Trucchia',
            'requestedAt' => $date->format(DateTime::FORMAT_STRING)
        ]);

        $expectedEvent = new TestEventFairy(new ValueObjectId('1'), StaticName::create('Francesco'), 'Trucchia', $date);
        $this->assertEquals($expectedEvent, $event);
    }

    /**
     * @test
     */
    public function deserializeWithoutCreatedAt()
    {
        $date  = new \DateTimeImmutable();
        $event = TestEventFairy::deserialize([
            'id'          => '1',
            'firstName'   => 'Francesco',
            'lastname'    => 'Trucchia',
            'requestedAt' => $date->format(DateTime::FORMAT_STRING)
        ]);

        $expectedEvent = new TestEventFairy(new ValueObjectId('1'), StaticName::create('Francesco'), 'Trucchia', $date);
        $this->assertEquals($expectedEvent, $event);
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Value id is not defined
     */
    public function deserializeWithInvalidData()
    {
        $date  = new \DateTimeImmutable();
        $event = TestEventFairy::deserialize([
            'foo'   => 'bar',
            'pluto' => 'paperino'
        ]);

        $expectedEvent = new TestEventFairy(new ValueObjectId('1'), StaticName::create('Francesco'), 'Trucchia',
            $date);
        $this->assertEquals($expectedEvent, $event);
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Call to undefined method BroadwayExtensions\Messaging\TestEventFairy::getNotDefinedMethod()
     */
    public function getterException()
    {
        $testEvent = new TestEventFairy(new ValueObjectId('1'), StaticName::create('Francesco'), 'Trucchia');
        $testEvent->getNotDefinedMethod();
    }
}

/**
 * Class TestEventFairy
 *
 * @method ValueObjectId getId
 * @method ValueObjectId id
 * @method string getFirstName
 * @method string getLastname
 * @method \DateTimeInterface getRequestedAt
 */
class TestEventFairy extends FairyDomainEvent
{
    public function __construct(
        ValueObjectId $id,
        StaticName $firstName,
        $lastname,
        \DateTimeInterface $requestedAt = null
    ) {
        $this->createdAt = $requestedAt;

        parent::__construct([
            'id'          => $id,
            'firstName'   => $firstName,
            'lastname'    => $lastname,
            'requestedAt' => $requestedAt
        ]);
    }
}

class ValueObjectId implements ValueObject
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return (string)$this->id;
    }

    function jsonSerialize()
    {
        return $this->__toString();
    }
}

class StaticName implements StaticValueObject
{
    private $value;

    public static function create($value)
    {
        return new self($value);
    }

    private function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return (string)$this->value;
    }

    function jsonSerialize()
    {
        return $this->__toString();
    }
}

/**
 * Class TestEventFairy
 *
 * @method ValueObjectId getId
 * @method ValueObjectId id
 */
class InvalidEventFairy extends FairyDomainEvent
{
    public function __construct(InvalidId $id)
    {
        parent::__construct([
            'id' => $id
        ]);
    }
}

class InvalidId
{

}
