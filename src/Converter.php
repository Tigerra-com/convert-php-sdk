<?php

namespace Tigerra;

class Converter extends BaseApiClient
{
    public function convert($conversionType, $filePath)
    {
        $endpoint = "/do-convert/{$conversionType}";
        return $this->sendRequest('POST', $endpoint, [], $filePath);
    }
}