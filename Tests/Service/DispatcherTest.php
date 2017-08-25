<?php
namespace Dmank\GearmanBundle\Tests\Service;

use dmank\gearman\Client;
use Dmank\GearmanBundle\Event\GearmanDispatchedEvent;
use Dmank\GearmanBundle\Event\GearmanDispatchEvent;
use Dmank\GearmanBundle\Event\GearmanEvent;
use Dmank\GearmanBundle\Service\Dispatcher;
use Dmank\GearmanBundle\Tests\Service\TestClass\TestEventSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testDirect()
    {
        /** @var Client | \PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('executeJob')
            ->with('jobName', ['test' => 'workload'], Client::PRIORITY_LOW)
            ->willReturn(true);

        $dispatchEvent = new GearmanDispatchEvent('jobName', ['test' => 'workload'], Client::PRIORITY_LOW);
        $dispatchedEvent = new GearmanDispatchedEvent('jobName', true);

        /** @var EventDispatcherInterface | \PHPUnit_Framework_MockObject_MockObject $eventDispatcher */
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [GearmanEvent::BEFORE_SYNC_EXECUTE, $dispatchEvent],
                [GearmanEvent::AFTER_SYNC_EXECUTE, $dispatchedEvent]
            );

        $dispatcher = new Dispatcher($client, $eventDispatcher);

        $this->assertTrue($dispatcher->direct('jobName', ['test' => 'workload']));
    }

    public function testInBackground()
    {
        /** @var Client | \PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('executeInBackground')
            ->with('jobName', ['test' => 'workload'], Client::PRIORITY_LOW)
            ->willReturn(true);

        $dispatchEvent = new GearmanDispatchEvent('jobName', ['test' => 'workload'], Client::PRIORITY_LOW);
        $dispatchedEvent = new GearmanDispatchedEvent('jobName', true);

        /** @var EventDispatcherInterface | \PHPUnit_Framework_MockObject_MockObject $eventDispatcher */
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [GearmanEvent::BEFORE_ASYNC_EXECUTE, $dispatchEvent],
                [GearmanEvent::AFTER_ASYNC_EXECUTE, $dispatchedEvent]
            );

        $dispatcher = new Dispatcher($client, $eventDispatcher);

        $this->assertTrue($dispatcher->inBackground('jobName', ['test' => 'workload']));
    }

    public function testManipulateWorkload()
    {
        /** @var Client | \PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('executeInBackground')
            ->with('jobName', ['test' => 'workload', 'ticketId' => 1], Client::PRIORITY_LOW)
            ->willReturn(true);

        $eventSubscriber = new TestEventSubscriber();

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($eventSubscriber);

        $dispatcher = new Dispatcher($client, $eventDispatcher);

        $this->assertTrue($dispatcher->inBackground('jobName', ['test' => 'workload']));
    }

}

