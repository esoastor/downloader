<?php

namespace Esoastor\Downloader\Base;


class DefaultDirHandler implements DirHandler
{
    public function handle(string $dirPath): void
    {
        if (is_dir($dirPath)) {
            return;
        }
        if (!mkdir($dirPath) && !is_dir($dirPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dirPath));
        }
    }
}