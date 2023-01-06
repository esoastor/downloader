<?php
namespace Downloader\Base\Default;

class InvalidConsoleReport implements \Downloader\Base\Listener
{
    public function execute(...$args): void
    {
        echo "[\e[0;31mX\e[0m] {$args[0]} \n";
    }
}