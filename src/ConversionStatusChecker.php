<?php

namespace Tigerra;

class ConversionStatusChecker extends BaseConverterApiClient
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_UPLOAD_ERROR = 'upload_error';
    public const STATUS_PROCESS_ERROR = 'process_error';
    public const STATUS_CONVERT_ERROR = 'convert_error';
    
    public function checkStatus($pid)
    {
        $endpoint = "/get-status/{$pid}";
        return $this->sendRequest('GET', $endpoint);
    }
}