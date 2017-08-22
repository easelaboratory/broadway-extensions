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

use BroadwayExtensions\TestCase;
use Webmozart\Assert\Assert;

class CommandBuilderTest extends TestCase
{
    private $classesMap;

    public function setUp()
    {
        $this->classesMap = [
            \DateTimeInterface::class => \DateTimeImmutable::class,
            StaticName::class => function ($value) {
                return StaticName::create((int)$value);
            }
        ];
    }

    /**
     * @test
     */
    public function map()
    {
        $params[] = '1';
        $params[] = 'Francesco';
        $params[] = '2017-01-01';

        $builder = new CommandBuilder();
        $builder->setClassesMap($this->classesMap);
        $command = $builder->build(TestCommand::class, $params);

        $this->assertInstanceOf(TestCommand::class, $command);
    }

    /**
     * @test
     * @expectedException \RangeException
     */
    public function invalidParamsRange()
    {
        $params[] = '1';
        $params[] = 'Francesco';
        $params[] = '2017-01-01';
        $params[] = '';

        $builder = new CommandBuilder();
        $builder->setClassesMap($this->classesMap);
        $builder->build(TestCommand::class, $params);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function invalidParamValue()
    {
        $params[] = 'a';
        $params[] = 'Francesco';
        $params[] = '2017-01-01';

        $builder = new CommandBuilder();
        $builder->setClassesMap($this->classesMap);
        $builder->build(TestCommand::class, $params);
    }
}

class ValueObjectId
{
    private $id;

    public function __construct($id)
    {
        Assert::numeric($id);
        $this->id = $id;
    }
}

class StaticName
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
}

class TestCommand
{
    private $id;
    private $name;
    private $date;

    public function __construct(ValueObjectId $id, StaticName $name, \DateTimeInterface $date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
    }
}
