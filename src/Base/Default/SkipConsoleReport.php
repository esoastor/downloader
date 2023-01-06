<?php
namespace Downloader\Base\Default;

class SkipConsoleReport implements \Downloader\Base\Listener
{
    public function execute(...$args): void
    {
        echo "[-] {$args[0]} \n";
    }
}