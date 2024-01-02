<?php

namespace Example;

use Downloader\DownloadInfoProvider;

class ExampleProvider implements DownloadInfoProvider
{

    public function provide(): array
    {
        return [];
    }
}