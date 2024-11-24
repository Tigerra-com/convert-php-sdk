<?php

namespace Tigerra;

class ConversionStatusChecker extends BaseApiClient
{
    public function checkStatus($pid)
    {
        $endpoint = "/get-status/{$pid}";
        return $this->sendRequest('GET', $endpoint);
    }
}