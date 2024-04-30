<?php

namespace DNT\VNPay;

if (!function_exists('secure_hash')) {
    function secure_hash(string $secret, array $params, string $algorithm = 'sha512'): string
    {
        return hash_hmac($algorithm, http_build_query($params), $secret);
    }
}
