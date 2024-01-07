<?php

namespace Esoastor\Downloader\Base;


class DefaultFileHandler implements FileHandler
{
    public function handle(string $filePath, string $file): void
    {
        file_put_contents($filePath, $file);
    }
}