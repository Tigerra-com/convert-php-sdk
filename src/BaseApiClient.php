<?php

namespace Tigerra;

class BaseApiClient
{
    protected $apiUrl = "https://convert.tigerra.com";
    protected $authToken;

    public function __construct($authToken)
    {
        $this->authToken = $authToken;
    }

    protected function sendRequest($method, $endpoint, $params = [], $filePath = null)
    {
        $url = $this->apiUrl . $endpoint;
        $headers = [
            "Authorization: Bearer {$this->authToken}"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'POST' && $filePath) {
            $file = new \CURLFile($filePath);
            $params['file'] = $file;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            throw new \Exception("HTTP Error: {$httpCode} - Response: {$response}");
        }

        return json_decode($response, true);
    }
}