<?php

namespace Esoastor\Downloader;

use Esoastor\Downloader\Base\DefaultFileHandler;
use Esoastor\Downloader\Base\FileHandler;

class Downloader
{
    private Events $events;
    private FileHandler $fileHandler;

    private string $errorText = '';
    private int $downloadAttempts = 10;

    /**
     *  is files overwritten if already existing
     */
    private bool $overwriteMode = false;

    /**
     * Folder to download passed to downloader structure
     */
    private string $rootFolder = '.';

    private bool $showDownloadProgress = false;

    private $downloadProgressCallback;

    public static function get(): self
    {
        $downloader = new self();

        $events = Events::get();
        $downloader->setEvents($events);

        $downloader->setFileHandler(new DefaultFileHandler());

        return $downloader;
    }

    public function enableConsoleReports(): void
    {
        $this->addListeners('Success', [Base\DefaultListeners\SuccessConsoleReport::class]);
        $this->addListeners('Skip', [Base\DefaultListeners\SkipConsoleReport::class]);
        $this->addListeners('Invalid', [Base\DefaultListeners\InvalidConsoleReport::class]);
        $this->addListeners('Error', [Base\DefaultListeners\ErrorConsoleReport::class]);
    }

    public function addListeners(string $eventName, array $listenerClassNames): void
    {
        $this->events->addListeners($eventName, $listenerClassNames);
    }

    public function setOverwriteMode(bool $mode): void
    {
        $this->overwriteMode = $mode;
    }

    public function setRootFolder(string $folder): void
    {
        $this->rootFolder = $folder;
    }

    public function setFileHandler(FileHandler $fileHandler): void
    {
        $this->fileHandler = $fileHandler;
    }

    /**
     * This callback can be used to create custom download progress bar
     */
    public function setDownloadCallback(callable $callback): void
    {
        $this->downloadProgressCallback = $callback;
    }

    public function showDownloadProgress(): void
    {
        $this->showDownloadProgress = true;
    }

    public function download(array $downloadInfo): void
    {
        if ($this->rootFolder !== '.') {
            $this->createDirIfNotExists($this->rootFolder);
        }

        $this->process($downloadInfo, $this->rootFolder);
    }

    private function process(array $structureInfo, string $folderToDownload): void
    {
        foreach ($structureInfo as $name => $content) {
            if (is_array($content)) {
                $dirName = $folderToDownload . '/' . $name;
                $this->createDirIfNotExists($dirName);
                $this->process($content, $dirName);
            } else {
                $url = $content;

                $filePath = $folderToDownload . '/' . $name;

                if ($this->overwriteMode === false && is_file($filePath) && filesize($filePath) > 0) {
                    $this->events->execute('Skip', $filePath);
                    continue;
                }

                $isURLValid = $this->validateURL($url);

                if (!$isURLValid) {
                    $this->events->execute('Invalid', $filePath);
                    continue;
                }

                $this->events->execute('Start', $filePath);

                $isDownloaded = $this->downloadFileToFolder($filePath, $url);

                if ($isDownloaded) {
                    $this->events->execute('Success', $filePath);
                } else {
                    $this->events->execute('Error', $filePath, $this->errorText);
                    $this->errorText = '';
                }
            }
        }
    }

    private function setEvents(Events $events): void
    {
        $this->events = $events;
    }


    private function downloadFileToFolder(string $filePath, string $url): bool
    {
        $attempts = $this->downloadAttempts;
        while (true) {
            $file = $this->fileGetContentCurl($url);

            if ($file !== '') {
                $this->fileHandler->handle($filePath, $file);
                return true;
            }

            if ($attempts > 0) {
                $this->errorText = "download error, attempts left: $attempts";
                --$attempts;
            } else {
                $this->errorText = "download failed";
                return false;
            }
        }
    }

    private function createDirIfNotExists(string $dirname): void
    {
        if (is_dir($dirname)) {
            return;
        }
        if (!mkdir($dirname, 0777, true) && !is_dir($dirname)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dirname));
        }
    }

    private function fileGetContentCurl(string $url): string
    {
        if (!function_exists('curl_init')) {
            echo 'CURL not installed';
            die;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($this->showDownloadProgress) {
            if (isset($this->downloadProgressCallback)) {
                curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, $this->downloadProgressCallback);
            } else {
                curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, [$this, 'defaultDownloadProgressCallback']);
            }
            curl_setopt($curl, CURLOPT_NOPROGRESS, false);
        }


        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function defaultDownloadProgressCallback($resource, $downloadSize, $downloaded, $uploadSize, $uploaded): void
    {
        static $previousProgress = 0;

        if ($downloadSize > 0) {
            $downloadedInMb = $this->formatFileSize($downloaded);
            $downloadSizeInMb = $this->formatFileSize($downloadSize);
            if (($downloadedInMb - $previousProgress) >= 1) {
                echo $downloadedInMb . ' mb / ' . $downloadSizeInMb . ' mb' . PHP_EOL;
                $previousProgress = $downloadedInMb;
            }
        }

        if ($downloaded === $downloadSize) {
            $previousProgress = 0;
        }

        flush();
    }

    private function validateURL(string $url): bool
    {
        $validationAttempts = 3;

        while (true) {
            $headers = get_headers($url, 1);

            if (!empty($headers["Content-Length"])) {
                return true;
            }

            if (($headers === False) && $validationAttempts > 0) {
                echo "validation error, attempts left: $validationAttempts\n";
                --$validationAttempts;
            } else {
                return false;
            }
        }
    }

    private function formatFileSize(float $fileSizeInBytes): float
    {
        return round($fileSizeInBytes / 1000000, 2);
    }
}
