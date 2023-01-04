<?php

namespace Downloader;

use Downloader\Events\EventsCollection;
use Downloader\DefaultReporter;

class Downloader 
{
    private object $events;
    private object $reporter;

    public static function get(bool $withReports = true, bool $withEvents = true): self
    {
        $downloader = new self();

        if ($withReports) {
            $reporter = new DefaultReporter();
            $downloader->setReporter($reporter);
        }

        if ($withEvents) {
            $events = EventsCollection::getDefault();
            $downloader->setEvents($events);
        }

        return $downloader;
    }

    public function setEvents(EventsCollection $events)
    {
        $this->events = $events;
    }

    public function setReporter(Reporter $reporter) 
    {
        $this->reporter = $reporter;
    }

    /**
     * @param $stryctureInfo принимает структуру вида [folder_name] => [file_name => file_link...], вложенность может быть любой
     */
    public function download(array $structureInfo, string $parentFolder = '', bool $overwrite = false) : void
    {
        foreach ($structureInfo as $name => $content)
        {
            $this->createDirIfNotExists($parentFolder);

            if (is_array($content))
            {
                $dirName = $parentFolder . '/' . $name;
                $this->createDirIfNotExists($dirName);
                $this->download($content, $dirName);
            }
            else
            {
                $url = $content;

                $filePath = $parentFolder . '/' . $name;

                if (is_file($filePath) && filesize($filePath) > 0 && $overwrite === false)
                {
                    $this?->reporter->skip($filePath, $url);
                    continue;
                }

                $isURLValid = $this->validateURL($url);

                if (!$isURLValid)
                {
                    $this?->reporter->invalid($filePath, $url);
                    continue;
                }
                
                $this?->events->execute('StartDownload', []);
                $this->downloadFileToFolder($filePath, $url);
                $this?->events->execute('FinishDownload', []);
                $this?->reporter->success($filePath, $url);
            }
        }
    }

    private function downloadFileToFolder(string $filePath, string $url, int $downloadAttempts = 3) : void
    {
        $downloadProcess = true;
        
        while ($downloadProcess)
        {
            $file = $this->fileGetContentCurl($url);

            if (mb_strlen($file) > 0)
            {
                file_put_contents($filePath, $file);
                $downloadProcess = false;
            }
            else 
            {
                if ($downloadAttempts > 0)
                {
                    $errorText = "downloading error, attempts left: $downloadAttempts\n";
                    $this?->reporter->error(basename($filePath), $url, $errorText);
                    $downloadAttempts -= 1;
                }
                else 
                {
                    $errorText = "downloading error: {$url}";
                    $this?->reporter->error(basename($filePath), $url, $errorText);
                    die;
                }
            }
        }
    }

    private function createDirIfNotExists(string $dirname) : void
    {
        if (!is_dir($dirname)) 
        {
            mkdir($dirname);
        }
    }

    private function fileGetContentCurl(string $url) : string
    {
        if (!function_exists('curl_init')) 
        {
            echo 'CURL not installed';
            die;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    
    private function validateURL(string $url, int $validationAttempts = 3) : bool
    {
        while (true)
        {
            $headers = get_headers($url, 1);
            
            if (!empty($headers["Content-Length"]))
            {
                return true;
            } 
            elseif ($headers === False)
            {
                if ($validationAttempts > 0)
                {
                    echo "validation error, attempts left: $validationAttempts\n";
                    $validationAttempts -= 1;
                }
                else 
                {
                    return false;
                }
            }
            else 
            {
                return false;
            }
        }
    }
    
}