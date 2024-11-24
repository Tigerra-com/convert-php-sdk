<?php

namespace Tigerra;

class ConverterDeletePid extends BaseConverterApiClient
{
    public function delete($pid)
    {
        return $this->sendRequest('DELETE', "/delete-pid/{$pid}");
    }
}