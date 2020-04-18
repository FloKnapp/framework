<?php

namespace Webasics\Framework\Tests\Fixtures;

use Webasics\Framework\EventDispatcher\EventSubscriberInterface;

/**
 * Class TestSubscriber
 * @package Framework\Tests\Fixtures
 */
class TestSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            ''
        ];
    }

}