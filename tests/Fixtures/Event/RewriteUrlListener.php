<?php

namespace Webasics\Tests\Fixtures\Event;

use Nyholm\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Webasics\Framework\App;
use Webasics\Framework\EventDispatcher\EventListenerInterface;

/**
 * Class TestListener
 * @package Framework\Tests\Fixtures
 */
class RewriteUrlListener implements EventListenerInterface
{

    public static function configure()
    {
        return [
            App::EVENT_REQUEST_CREATE => [
                'onRequestCreate', 10
            ]
        ];
    }

    /**
     * @param RequestInterface $request
     */
    public function onRequestCreate(RequestInterface &$request)
    {
        $request = $request->withUri(new Uri('/test/dynamic'));
    }

}