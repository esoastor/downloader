# Downloader with Events and Reporter #

## Usage ##

Create class that implements `Downloader\DownloadInfoProvider` interface. 
Object of this class provides structure to download: `[folder_name] => [file_name => file_link...]` (nesting level not restricted).  

Get downloader with `Downloader::get()`. Use `enableConsoleReports()` to enable console report (messages about success/error etc).

Configurate custom event handlers if needed (see **event listeners**).

```php
$provider = new MyProvider();
$downloader = \Downloader\Downloader::get();
$downloader->enableConsoleReports();
$downloader->showDownloadProgress();
$downloader->setRootFolder('/home/users/me/Download/TargetFolder');
$downloader->setOverwriteMode(true);

$downloader->download($provider);
```

## Downloader configuration

### enableConsoleReports()
Enables console reports of 'Success', 'Skip', 'Invalid' and 'Error' events triggering. 

### setOverwriteMode(bool $mode)
Overwrite mode allows to overwrite already downloaded files (is structure from **DownloadInfoProvider** already exists). 

Default value - **false** (files are not overwritten).

### setRootFolder(string $folder)
Sets folder that will be used for downloading structure that provided by **DownloadInfoProvider**

Default value - **'.'** (current folder).

### showDownloadProgress() and setDownloadCallback(callable $callback)
`showDownloadProgress()` shows console report of file download progress. 
Custom progress report callback will be used is setted (by `setDownloadCallback` method), if not - default report callback will be used.

### Event listeners ###
There are four types of events - 'Start', 'Success', 'Error', 'Skip', 'Invalid'
1 - create event listener (Downloader\Base\Listener interface). 
2 - create listeners. add them with addListeners method of Download class

Custom listeners method `execute` will be called on event trigger.

```
$downloader->addListeners('Success', [MyListeners\Success::class]);
$downloader->addListeners('Error', [MyListeners\Error::class]);
$downloader->addListeners('Invalid', [MyListeners\Invalid::class]);
```

## Docker environment
Can be used by docker. Content of example folder can be used as base.