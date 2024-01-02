<?php

namespace Downloader;

/**
 * Provides array with structure to download
 */
interface DownloadInfoProvider
{
    /**
     * @return array [folder_name] => [file_name => file_link...], вложенность может быть любой
     */
    public function provide(): array;
}