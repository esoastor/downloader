<?php

namespace Example;

use Downloader\DownloadInfoProvider;

class ExampleProvider implements DownloadInfoProvider
{

    public function provide(): array
    {
        return ['here' => ['a.png' => 'https://scans-hot.leanbox.us/manga/Chainsaw-Man/0151-001.png', 'b.png' => 'https://scans-hot.leanbox.us/manga/Chainsaw-Man/0151-002.png']];
    }
}