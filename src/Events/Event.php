<?php

namespace Downloader\Events;

abstract class Event 
{
    protected array $listeners;

    public function excecute(...$args): void
    {
        foreach ($this->listeners as $listener) {
            $listener->execute($args);
        }
    }
}