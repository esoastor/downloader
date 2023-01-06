# Downloader with Events and Reporter #

## Usage ##
Get standart downloader with Downloader::get(), or configurate custom event handlers and/or custom reporter

### event listeners: ###
There are four types of events - 'Start', 'Success', 'Error', 'Invalid'
1 - create event listener (Downloader\Base\Listener interface). 
2 - create listeners. add them with addListeners method of Download class

```
$downloader->addListeners('Success', [Listeners\Success::class]);
$downloader->addListeners('Error', [Listeners\Error::class]);
$downloader->addListeners('Invalid', [Listeners\Invalid::class]);
```

### custom reporter ### 
1 - create class that implements Downloader\Base\Reporter
2 - set it to instance of downloader with setReporter()