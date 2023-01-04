<?php

namespace Downloader\Events;

class EventsCollection
{
    private array $events;

    public static function getDefault(): self
    {
        $collection = new self();
        $collection->setEvents(
            [
                StartDownload::class => [], 
                FinishDownload::class => [],
            ]
        );

        return $collection;
    }

    public function setEvents(array $events): void
    {
        $this->events = $events;
    }

    public function addListeners(array $eventName, array $listeners): void
    {
        $fullEventName = __NAMESPACE__ . '\\' . $eventName;
        $this->events[$fullEventName] = array_merge($this->events[$fullEventName], $listeners);
    }

    public function execute(string $eventName, array $args): void
    {
        $listeners = $this->events[__NAMESPACE__ . '\\' . $eventName];
        foreach ($listeners as $listener)
        {
            $listener->execute($args);
        }
    }
}