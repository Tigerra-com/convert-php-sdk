# Tigerra Converter PHP-SDK

## Overview

This documentation provides an overview of the Tigerra Converter library, which allows for various file conversions including audio, video, image, document, and font conversions. Additionally, it supports applying audio effects.

## Installation

To use the Tigerra Converter library, you need to include the required dependencies using Composer:

```sh
composer require tigerra/convert
```

## Usage

### Basic Example

Below is an example of how to use the Tigerra Converter php-sdk to convert an audio file from FLAC to WAV:

```php
<?php

require 'vendor/autoload.php';

use Tigerra\Converter;
use Tigerra\ConversionStatusChecker;
use Tigerra\ConverterFileDownloader;
use Tigerra\ConverterDeletePid;

try {
    $authToken =  "your-auth-token";

    // Start conversion
    echo "Uploading file..\n";
    $converter = new Converter($authToken);
    $conversionResponse = $converter->audio("flac-to-wav", "/path/to/file/1.flac");
    $pid = $conversionResponse['pid'];
    echo "Conversion started. PID: {$pid}\n";

    // Check status
    $statusChecker = new ConversionStatusChecker($authToken);
    do {
        $statusResponse = $statusChecker->checkStatus($pid);
        $status = $statusResponse['status'];
        echo "Status: " . $status . "\n";
    
        if ($status === ConversionStatusChecker::STATUS_COMPLETED) {
            break;
        } elseif (in_array($status, [
            ConversionStatusChecker::STATUS_UPLOAD_ERROR,
            ConversionStatusChecker::STATUS_PROCESS_ERROR,
            ConversionStatusChecker::STATUS_CONVERT_ERROR
        ])) {
            throw new \Exception("Error occurred: " . $status);
        }
    
        sleep(2);
    } while ($status === ConversionStatusChecker::STATUS_PENDING);

    // Download file
    $downloadUrl = $statusResponse['data'];
    $downloader = new ConverterFileDownloader($authToken);
    $outputPath = "/path/to/file/downloaded_file.wav";
    $downloader->downloadFile($downloadUrl, $outputPath);
    echo "File downloaded to: $outputPath\n";

    // Delete PID
    $deletePid = new ConverterDeletePid($authToken);
    $r = $deletePid->delete($pid);
    if($r['success'] === true) {
        echo "PID deleted successfully.";
    } else {
        echo "Error deleting PID: " . $r['message'];
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```


## Converter Class

The Converter class provides methods for different types of conversions:

- audio($conversionType, $filePath)
- video($conversionType, $filePath)
- image($conversionType, $filePath)
- document($conversionType, $filePath)
- font($conversionType, $filePath)
- pdf_compress($filePath)
- audio_effect($effectType, $filePath, $params = [])

Each method sends a request to the appropriate endpoint to perform the conversion or apply the effect. About the audio_effect method types nad $conversionType's, read them from <a href="https://tigerra.com/convert-api-documentation">tiggera.com documentation</a>.
