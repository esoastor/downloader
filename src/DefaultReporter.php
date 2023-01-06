<?php

namespace Downloader;

class DefaultReporter implements Base\Reporter
{
    public function success(string $fileName, string $url): void
    {
        echo "[\e[0;32mV\e[0m] {$fileName} \n";
    }

    public function skip(string $fileName, string $url): void
    {
        echo "[-] {$fileName} \n";
    }

    public function invalid(string $fileName, string $url): void
    {
        echo "[\e[0;31mX\e[0m] {$fileName} \n";
    }

    public function error(string $fileName, string $url, string $errorMessage): void
    {
        echo "[\e[0;31mX\e[0m] {$fileName} [{$errorMessage}]\n";
    }
}
