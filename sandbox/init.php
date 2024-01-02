<?php
include_once '../vendor/autoload.php';

use \Esoastor\Downloader\Sandbox\ExampleProvider;

$provider = new ExampleProvider();
$downloader = \Esoastor\Downloader\Downloader::get();
$downloader->enableConsoleReports();
$downloader->showDownloadProgress();

$downloader->download($provider);