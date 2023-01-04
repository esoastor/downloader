# Downloader with Events and Reporter #

## Usage ##
Get standart downloader with Downloader::get(), or configurate custom event handlers and/or custom reporter

### custom events: ###
1 - create EventsCollection. There are two types of events - StartDownload and FinishDownload
2 - create listeners. add them with addListeners method of EventsCollection
3 - create downloader, set EventsCollection with 

### custom reporter ### 
1 - create class that implements Downloader\Reporter
2 - set it to instance of downloader with setReporter()