<?php
/*
 * This file is part of the francescotrucchia/soisy package.
 *
 * (c) Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayExtensions\EventSourcing;

use Broadway\EventHandling\EventBus;
use Broadway\EventSourcing\AggregateFactory\AggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository as BaseEventSourcingRepository;
use Broadway\EventSourcing\EventStreamDecorator;
use Broadway\EventStore\EventStore;

class EventSourcingRepository extends BaseEventSourcingRepository implements Repository
{
    private $type;

    /**
     * @param EventStore             $eventStore
     * @param EventBus               $eventBus
     * @param string                 $aggregateClass
     * @param AggregateFactory       $aggregateFactory
     * @param EventStreamDecorator[] $eventStreamDecorators
     */
    public function __construct(
        EventStore $eventStore,
        EventBus $eventBus,
        $aggregateClass,
        AggregateFactory $aggregateFactory,
        array $eventStreamDecorators = []
    ) {
        $this->type = $aggregateClass;

        parent::__construct(
            $eventStore,
            $eventBus,
            $aggregateClass,
            $aggregateFactory,
            $eventStreamDecorators
        );
    }

    public function getType()
    {
        return $this->type;
    }
}
