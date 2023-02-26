<?php
namespace Downloader\Base\Default;

class ErrorConsoleReport implements \Downloader\Base\Listener
{
    public function execute(...$args): void
    {
        $message = "[\e[0;31mX\e[0m] {$args[0]}";
        $message .= isset($args[1]) ? " [{$args[1]}]\n" : "\n"; 
        echo $message;
    }
}