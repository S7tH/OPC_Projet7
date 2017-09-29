<?php

namespace AppBundle\Serializer\Listener;

use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class ProducesListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return[
            [
                'event' => Events::POST_SERIALIZE,
                'format' => 'json',
                'class' => 'AppBundle\Entity\Produces',
                'method' => 'onPostSerialize',
            ]
        ];
    }

    public static function onPostSerialize(ObjectEvent $event)
    {
        $status = "in beta test";
        $event->getVisitor()->addData('api_state', $status);
    }
}
