<?php

namespace Downloader;

class DefaultReporter implements Reporter
{
    public function success(string $fileName, string $url): void
    {
        echo "[\e[0;32mV\e[0m] {$url} \n";
    }

    public function skip(string $fileName, string $url): void
    {
        echo "[-] {$url} \n";
    }

    public function invalid(string $fileName, string $url): void
    {
        echo "[\e[0;31mX\e[0m] {$url} \n";
    }

    public function error(string $fileName, string $url, string $errorMessage): void
    {

    }
}
