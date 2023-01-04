<?php

namespace Downloader;

class Events
{
    private array $events;

    public static function get(): self
    {
        $events = new self();
        $events->setEvents(
            [
                'StartDownload' => [], 
                'FinishDownload' => [],
            ]
        );

        return $events;
    }

    private function setEvents(array $events): void
    {
        $this->events = $events;
    }

    public function addListeners(string $eventName, array $listeners): void
    {
        $this->events[$eventName] = array_merge($this->events[$eventName], $listeners);
    }

    public function execute(string $eventName, array $args): void
    {
        $listeners = $this->events[$eventName];
        foreach ($listeners as $listener)
        {
            $listener::execute($args);
        }
    }
}