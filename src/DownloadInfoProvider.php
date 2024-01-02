<?php

namespace Esoastor\Downloader;

/**
 * Provides array with structure to download
 */
interface DownloadInfoProvider
{
    /**
     * @return array
     */
    public function provide(): array;
}