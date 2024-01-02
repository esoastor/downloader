<?php

namespace Esoastor\Downloader\Base;

interface Listener
{
    public function execute(mixed ...$args): void;
}