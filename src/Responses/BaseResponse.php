<?php

namespace DNT\VNPay\Responses;

use Saloon\Http\Response;

class BaseResponse extends Response
{
    public function isRedirect(): bool
    {
        return in_array($this->status(), [301], true);
    }
}
