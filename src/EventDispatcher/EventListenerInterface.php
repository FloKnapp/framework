<?php


namespace Webasics\Framework\EventDispatcher;


interface EventListenerInterface
{

    /**
     * Returns an array of subscribed events.
     *
     * Example for single target method:
     *
     * return [
     *     '{eventName}' => ['{targetMethod}', 0]
     * ];
     *
     * Example with multi target methods:
     *
     * return [
     *     '{eventName}' => [
     *         ['{targetMethod1}', 0],
     *         ['{targetMethod2}, 10],
     *         ['{targetMethod3}, 20]
     *     ]
     * ];
     *
     * @return array
     */
    public static function configure();

}