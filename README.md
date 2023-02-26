# Downloader with Events and Reporter #

## Usage ##
Get standart downloader with Downloader::get(), use enableDefaultReports() to enable console report, configurate custom event handlers if needed

### event listeners: ###
There are four types of events - 'Start', 'Success', 'Error', 'Skip', 'Invalid'
1 - create event listener (Downloader\Base\Listener interface). 
2 - create listeners. add them with addListeners method of Download class

```
$downloader->addListeners('Success', [Listeners\Success::class]);
$downloader->addListeners('Error', [Listeners\Error::class]);
$downloader->addListeners('Invalid', [Listeners\Invalid::class]);
```
### download: ###
$stryctureInfo : [folder_name] => [file_name => file_link...], вложенность может быть любой

$downloader->download($stryctureInfo, $parentFolder, $overwrite); 
