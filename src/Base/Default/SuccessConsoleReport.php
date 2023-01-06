<?php
namespace Downloader\Base\Default;

class SuccessConsoleReport implements \Downloader\Base\Listener
{
    public function execute(...$args): void
    {
        echo "[\e[0;32mV\e[0m] {$args[0]} \n";
    }
}