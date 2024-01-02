<?php
namespace Esoastor\Downloader\Base\Default;

class InvalidConsoleReport implements \Esoastor\Downloader\Base\Listener
{
    public function execute(...$args): void
    {
        echo "[\e[0;31mX\e[0m] {$args[0]} \n";
    }
}