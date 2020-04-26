<?php

namespace Webasics\Framework\Event;

/**
 * Class Observer
 * @package Framework\EventDispatcher
 */
class Observer
{

    /** @var EventListenerInterface[] */
    private array $listeners;

    /**
     * Observer constructor.
     * @param array $listeners
     */
    public function __construct(array $listeners = [])
    {
        $this->listeners = $listeners;
    }

    /**
     * Notify subscribers
     *
     * @param string $eventName
     * @param mixed  $payload
     *
     * @return array
     */
    public function notify(string $eventName, &$payload = null)
    {
        $result    = [];
        $listeners = $this->getListenerByEventName($eventName);

        foreach ($listeners as $listener) {

            // Execute
            $result[get_class($listener['object'])] = ($listener['object'])->{$listener['action']}($payload);

        }

        return $result;
    }

    /**
     * @param string $eventName
     *
     * @return array
     */
    private function getListenerByEventName(string $eventName): array
    {
        $result = [];

        /** @var EventListenerInterface $listener */
        foreach ($this->listeners as $listener) {

            $subscribedEvents = $listener::configure();

            foreach ($subscribedEvents as $event => $eventData) {

                if ($event !== $eventName) {
                    continue;
                }

                list($action, $priority) = $eventData;

                $result[$eventName][] = [
                    'object'   => new $listener(),
                    'action'   => $action,
                    'priority' => $priority
                ];

            }

        }

        return $result[$eventName] ?? [];
    }

}