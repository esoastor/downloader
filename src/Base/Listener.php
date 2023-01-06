<?php

namespace Downloader\Base;

interface Listener
{
    public function execute(mixed ...$args): void;
}