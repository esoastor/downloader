<?php
namespace Esoastor\Downloader\Base\DefaultListeners;

class SkipConsoleReport implements \Esoastor\Downloader\Base\Listener
{
    public function execute(...$args): void
    {
        echo "[-] {$args[0]} \n";
    }
}