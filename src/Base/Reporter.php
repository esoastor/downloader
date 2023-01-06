<?php

namespace Downloader\Base;

interface Reporter 
{
    public function success(string $fileName, string $url): void;

    public function skip(string $fileName, string $url): void;

    public function invalid(string $fileName, string $url): void;

    public function error(string $fileName, string $url, string $errorMessage): void;
}