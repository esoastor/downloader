<?php

namespace Esoastor\Downloader\Base;


interface DirHandler
{
    /*
     * Do something with this recieved $file 
     */
    public function handle(string $dirPath): void;
}