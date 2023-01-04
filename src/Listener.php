<?php

namespace Downloader;

interface Listener
{
    public static function excecute(...$args): void;
}