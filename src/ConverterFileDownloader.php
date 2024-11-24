<?php

namespace Tigerra;


class ConverterFileDownloader extends BaseApiClient
{
    public function downloadFile($downloadUrl, $outputPath)
    {
        $ch = curl_init($downloadUrl);
        $headers = [
            "Authorization: Bearer {$this->authToken}"
        ];

        $file = fopen($outputPath, 'w');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FILE, $file);

        $success = curl_exec($ch);
        curl_close($ch);
        fclose($file);

        if (!$success) {
            throw new \Exception("Failed to download file.");
        }

        return $outputPath;
    }
}