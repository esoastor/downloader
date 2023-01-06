<?php

namespace Downloader;

class DefaultReporter implements Base\Reporter
{
    public function success(string $filePath, string $url): void
    {
        echo "[\e[0;32mV\e[0m] {$filePath} \n";
    }

    public function skip(string $filePath, string $url): void
    {
        echo "[-] {$filePath} \n";
    }

    public function invalid(string $filePath, string $url): void
    {
        echo "[\e[0;31mX\e[0m] {$filePath} \n";
    }

    public function error(string $filePath, string $url, string $errorMessage): void
    {
        echo "[\e[0;31mX\e[0m] {$filePath} \n";
        echo $errorMessage . "\n";
    }
}
