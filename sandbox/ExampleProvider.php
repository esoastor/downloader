<?php

namespace Esoastor\Downloader\Sandbox;

use \Esoastor\Downloader\DownloadInfoProvider;

class ExampleProvider implements DownloadInfoProvider
{
    public function provide(): array
    {
        return ['here' => ['file.bin' => 'https://ash-speed.hetzner.com/1GB.bin']];
    }
}