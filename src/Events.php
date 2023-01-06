<?php

namespace Downloader;

class Events
{
    private array $events;
    private array $listenerInstances;

    public static function get(): self
    {
        $events = new self();
        $events->events = [
                'Start' => [], 
                'Success' => [],
                'Skip' => [],
                'Error' => [],
                'Invalid' => [],
            ];
        $events->listenerInstances = [];

        return $events;
    }

    public function addListeners(string $eventName, array $listeners): void
    {
        $this->events[$eventName] = array_merge($this->events[$eventName], $listeners);
    }

    public function execute(string $eventName, mixed $args): void
    {
        $listeners = $this->events[$eventName];
        foreach ($listeners as $listener)
        {
            if (!isset($this->listenerInstances[$listener])) {
                $this->listenerInstances[$listener] = new $listener;
            }
            $this->listenerInstances[$listener]->execute($args);
        }
    }
}