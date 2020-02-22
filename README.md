# FileStorage
This is a library to store data remote of the webserver, when working with microservices and or serverless technology
the need for a centralized service is needed for shared files.
This is designed for slow changing IO file activity. 

## Installation
This library can be installed by composer.
```
composer required jorisros/file-storage
```

## Usage
Create a transporter in this the local file system
and add there a PSR compatible logger. And configure the base directory where the transport layer will store its data.

```php
$parameters = [
    'baseDirectory' => $tempDirectory
];

$transport = new \JorisRos\FileStorage\Transport\LocaleFile($parameters, $logger);

$fileStorage = new FileStorageService($transport);
$fileStorage->setFileContent('test.txt', 'This is a test');
$data = $fileStorage->getFileContent('test.txt');

echo $data; // Will be 'This is a test' printed
```

## TODO
- Add AWS S3 client
- Add Dropbox client