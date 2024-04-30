<?php

use DNT\VNPay\VNPay;

it('Tạo URL thanh toán', function () {
    $client = VNPay::sandbox([
        'secret' => 'OSZZNYPDNEIEQPLLETIIIDQSSSYHXNPD',
        'tmn_code' => '1G7817OE',
        'verify'=>false
    ]);
    expect($client->purchase([
        'amount' => 5000,
        'bank_code' => 'NCB',
        'ip_address' => '127.0.0.1',
        'message' => 'Thanh toán đơn hàng #123',
        'txn_ref' => rand(1111,9999),
        'callback_url' => 'http://ducconit.test',
        'order_type' => 'other'
    ])->status())->toBe(301);
});
