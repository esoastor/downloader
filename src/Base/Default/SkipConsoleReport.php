<?php
namespace Esoastor\Downloader\Base\Default;

class SkipConsoleReport implements \Esoastor\Downloader\Base\Listener
{
    public function execute(...$args): void
    {
        echo "[-] {$args[0]} \n";
    }
}