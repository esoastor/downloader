<?php

namespace Downloader;


class Downloader
{
    private object $events;
    private string $errorText = '';
    private int $downloadAttempts = 10;

    public static function get(): self
    {
        $downloader = new self();

        $events = Events::get();
        $downloader->setEvents($events);

        return $downloader;
    }

    public function enableDefaultReports()
    {
        $this->addListeners('Success', [Base\Default\SuccessConsoleReport::class]);
        $this->addListeners('Skip', [Base\Default\SkipConsoleReport::class]);
        $this->addListeners('Invalid', [Base\Default\InvalidConsoleReport::class]);
        $this->addListeners('Error', [Base\Default\ErrorConsoleReport::class]);
    }

    public function setEvents(Events $events)
    {
        $this->events = $events;
    }

    public function addListeners(string $eventName, array $listenerClassNames)
    {
        $this->events->addListeners($eventName, $listenerClassNames);
    }

    /**
     * @param $stryctureInfo принимает структуру вида [folder_name] => [file_name => file_link...], вложенность может быть любой
     */
    public function download(array $structureInfo, string $parentFolder = '.', bool $overwrite = false): void
    {
        if ($parentFolder !== '.') {
            $this->createDirIfNotExists($parentFolder);
        }

        foreach ($structureInfo as $name => $content) {
            if (is_array($content)) {
                $dirName = $parentFolder . '/' . $name;
                $this->createDirIfNotExists($dirName);
                $this->download($content, $dirName);
            } else {
                $url = $content;

                $filePath = $parentFolder . '/' . $name;

                if (is_file($filePath) && filesize($filePath) > 0 && $overwrite === false) {
                    $this->events->execute('Skip', $name);
                    continue;
                }

                $isURLValid = $this->validateURL($url);

                if (!$isURLValid) {
                    $this->events->execute('Invalid', $name);
                    continue;
                }

                $this->events->execute('Start', $name);

                $isDownloaded = $this->downloadFileToFolder($filePath, $url);

                if ($isDownloaded) {
                    $this->events->execute('Success', $name);
                } else {
                    $this->events->execute('Error', $name, $this->errorText);
                    $this->errorText = '';
                }
            }
        }
    }

    private function downloadFileToFolder(string $filePath, string $url): bool
    {
        $attempts = $this->downloadAttempts;
        while (true) {
            $file = $this->fileGetContentCurl($url);

            if (mb_strlen($file) > 0) {
                file_put_contents($filePath, $file);
                return true;
            } else {
                if ($attempts > 0) {
                    $this->errorText = "download error, attempts left: $attempts";
                    $attempts -= 1;
                } else {
                    $this->errorText = "download failed";
                    return false;
                }
            }
        }
    }

    private function createDirIfNotExists(string $dirname): void
    {
        if (!is_dir($dirname)) {
            mkdir($dirname);
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
        curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, static function ($resource, $download_size, $downloaded, $upload_size, $uploaded) {
            if ($download_size > 0)
                echo $downloaded / $download_size  * 100;
            ob_flush();
            flush();
            sleep(1);
        });
        curl_setopt($curl, CURLOPT_NOPROGRESS, false);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    private function validateURL(string $url, int $validationAttempts = 3): bool
    {
        while (true) {
            $headers = get_headers($url, 1);

            if (!empty($headers["Content-Length"])) {
                return true;
            } elseif ($headers === False) {
                if ($validationAttempts > 0) {
                    echo "validation error, attempts left: $validationAttempts\n";
                    $validationAttempts -= 1;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
}
