<?php

namespace Downloader\Listeners;

interface Listener
{
    public static function excecute(...$args): void;
}