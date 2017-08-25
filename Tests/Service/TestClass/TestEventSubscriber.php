<?php

namespace Dmank\GearmanBundle\Tests\Service\TestClass;


use Dmank\GearmanBundle\Event\GearmanDispatchEvent;
use Dmank\GearmanBundle\Event\GearmanEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TestEventSubscriber implements EventSubscriberInterface
{
    public function update(GearmanDispatchEvent $event)
    {
        $workload = $event->getWorkload();
        $workload['ticketId'] = 1;
        $event->setWorkload($workload);
    }

    public static function getSubscribedEvents()
    {
        return [
            GearmanEvent::BEFORE_ASYNC_EXECUTE => 'update'
        ];
    }

}
