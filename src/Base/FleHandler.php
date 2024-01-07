<?php

namespace Esoastor\Downloader\Base;


interface FileHandler
{
    /*
     * Do something with this recieved $file 
     */
    public function handle(string $filePath, string $file);
}