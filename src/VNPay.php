<?php

namespace DNT\VNPay;

use DNT\VNPay\Requests\PurchaseRequest;
use Saloon\Http\Connector;
use Saloon\Http\Response;

class VNPay extends Connector
{
    /**
     * @param string $host
     * @param array $config
     */
    public function __construct(string $host, array $config = [])
    {
        $this->config()->merge([...$config, 'host' => $host]);
    }

    public function resolveBaseUrl(): string
    {
        return $this->config()->get('host');
    }

    /**
     * @param array $config
     */
    public static function sandbox(array $config = [])
    {
        return new static('https://sandbox.vnpayment.vn', $config);
    }

    public function purchase(array $data): Response
    {
        return $this->send(new PurchaseRequest(array_merge([
            'secret' => $this->config()->get('secret'),
            'tmn_code' => $this->config()->get('tmn_code'),
        ], $data)));
    }
}
