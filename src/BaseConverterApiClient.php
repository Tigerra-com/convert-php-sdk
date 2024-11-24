<?php

namespace Tigerra;

class BaseConverterApiClient
{
    protected const API_URL = "https://convert.tigerra.com";
    protected $authToken;

    public function __construct($authToken)
    {
        $this->authToken = $authToken;
    }

    protected function sendRequest($method, $endpoint, $params = [], $filePath = null)
    {
        if(!extension_loaded('curl')) {
            throw new \Exception("cURL extension is not loaded");
        }
        if(!class_exists('CURLFile')) {
            throw new \Exception("CURLFile class is not available");
        }

        $url = self::API_URL . $endpoint;

        $headers = [
            "Authorization: Bearer {$this->authToken}"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300); // Set timeout

        if ($method === 'POST' && $filePath) {
            $file = new \CURLFile($filePath);
            if (!file_exists($file->name)) {
                throw new \Exception("File not found: {$file->name}");
            }

            $params['file'] = $file;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        curl_close($ch);

        if ($curlError) {
            throw new \Exception("cURL Error: {$curlError}");
        }

        if ($httpCode !== 200) {
            throw new \Exception("HTTP Error: {$httpCode} - Response: {$response}");
        }

        $response = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("JSON Error: " . json_last_error_msg());
        }

        return $response;
    }
}