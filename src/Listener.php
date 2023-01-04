<?php

namespace Downloader;

interface Listener
{
    public static function execute(...$args): void;
}