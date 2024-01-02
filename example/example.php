<?php
include_once '../vendor/autoload.php';
include_once 'ExampleProvider.php';

use Example\ExampleProvider;

$provider = new ExampleProvider();
$downloader = \Downloader\Downloader::get();
$downloader->enableConsoleReports();
$downloader->showDownloadProgress();

$downloader->download($provider);