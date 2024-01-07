# Downloader with Events and Reporter #

## Usage ##

Create array with structure to download: `[folder_name => [file_name => file_link...]` (nesting level not restricted).  

Get downloader with `Downloader::get()`. Use `enableConsoleReports()` to enable console report (messages about success/error etc).

Configurate custom event handlers if needed (see **event listeners**).

```php
$downloadData = ['images' => ['img1.png' => 'www.img/img1.png']];
$downloader = \Downloader\Downloader::get();
$downloader->enableConsoleReports();
$downloader->showDownloadProgress();
$downloader->setRootFolder('/home/users/me/Download/TargetFolder');
$downloader->setOverwriteMode(true);

$downloader->download($downloadData);
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

### setFileHandler(FileHandler $fileHandler) thing that do something with recieved file
By default $file will be saved in $filePath with 'file_put_content'. You cab make your file handler.
To do so create class that implements Esoastor\Downloader\Base\FileHandler interface and set its object to `downloader` with `setFileHandler()`

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
