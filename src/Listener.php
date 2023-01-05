<?php

namespace Downloader;

interface Listener
{
    public static function execute(mixed ...$args): void;
}