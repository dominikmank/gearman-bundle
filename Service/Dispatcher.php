<?php
namespace Dmank\GearmanBundle\Service;

use dmank\gearman\Client;
use Dmank\GearmanBundle\Event\GearmanDispatchedEvent;
use Dmank\GearmanBundle\Event\GearmanDispatchEvent;
use Dmank\GearmanBundle\Event\GearmanEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Dispatcher implements DispatcherInterface
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(Client $client, EventDispatcherInterface $eventDispatcher)
    {
        $this->client = $client;
        $this->eventDispatcher = $eventDispatcher;
    }


    public function direct($jobName, $workLoad, $priority = DispatcherInterface::PRIORITY_LOW)
    {
        $dispatchEvent = new GearmanDispatchEvent($jobName, $workLoad, $priority);

        $this->eventDispatcher->dispatch(GearmanEvent::BEFORE_SYNC_EXECUTE, $dispatchEvent);
        $result = $this->client->executeJob(
            $dispatchEvent->getJobname(),
            $dispatchEvent->getWorkload(),
            $dispatchEvent->getPriority()
        );
        $this->eventDispatcher->dispatch(GearmanEvent::AFTER_SYNC_EXECUTE, new GearmanDispatchedEvent($jobName, $result));

        return $result;
    }

    public function inBackground($jobName, $workLoad, $priority = DispatcherInterface::PRIORITY_LOW)
    {
        $dispatchEvent = new GearmanDispatchEvent($jobName, $workLoad, $priority);

        $this->eventDispatcher->dispatch(GearmanEvent::BEFORE_ASYNC_EXECUTE, $dispatchEvent);
        $result = $this->client->executeInBackground(
            $dispatchEvent->getJobname(),
            $dispatchEvent->getWorkload(),
            $dispatchEvent->getPriority()
        );
        $this->eventDispatcher->dispatch(GearmanEvent::AFTER_ASYNC_EXECUTE, new GearmanDispatchedEvent($jobName, $result));

        return $result;
    }
}
