<?php

namespace DNT\VNPay\Requests;

use DateInterval;
use DateTime;
use DateTimeZone;
use DNT\VNPay\Responses\PurchaseResponse;
use InvalidArgumentException;
use Saloon\Enums\Method;
use Saloon\Http\Request;

use function DNT\VNPay\secure_hash;

class PurchaseRequest extends Request
{
    protected Method $method = Method::GET;

    protected ?string $response = PurchaseResponse::class;

    public function __construct(array $data)
    {
        $this->config()->merge($data);
    }

    public function resolveEndpoint(): string
    {
        return 'paymentv2/vpcpay.html';
    }

    public function defaultQuery(): array
    {
        $params = $this->buildParams();
        ksort($params);
        return [
            ...$params,
            'vnp_SecureHash' => secure_hash($this->getSecret(), $params)
        ];
    }

    public function buildParams(): array
    {
        return [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $this->getTmnCode(),
            'vnp_Amount' => $this->getAmount(),
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => $this->getCreateDate(),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $this->getIpAddress(),
            'vnp_Locale' => $this->getLocale(),
            'vnp_OrderInfo' => $this->getMessage(),
            'vnp_OrderType' => $this->getOrderType(),
            'vnp_ReturnUrl' => $this->getCallbackUrl(),
            'vnp_TxnRef' => $this->getTxnRef(),
            'vnp_ExpireDate' => $this->getExpiredDate(),
            'vnp_BankCode' => $this->getBankCode(),
        ];
    }

    public function getTimezone(): DateTimeZone
    {
        $tz = $this->config()->get('timezone', 'Asia/Ho_Chi_Minh');
        if ($tz instanceof DateTimeZone) {
            return $tz;
        }
        return new DateTimeZone($tz);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getOrderType(): string
    {
        if ($orderType = $this->config()->get('order_type')) {
            return $orderType;
        }

        throw new InvalidArgumentException('Không tìm thấy thuộc tính order_type. Xem thêm tại: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html#tao-url-thanh-toan');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getSecret(): string
    {
        if ($secret = $this->config()->get('secret')) {
            return $secret;
        }

        throw new InvalidArgumentException('Không tìm thấy thuộc tính secret. Xem thêm tại: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html#tao-url-thanh-toan');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getTxnRef(): string
    {
        if ($txnRef = $this->config()->get('txn_ref')) {
            return $txnRef;
        }

        throw new InvalidArgumentException('Không tìm thấy thuộc tính txn_ref. Xem thêm tại: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html#tao-url-thanh-toan');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getCallbackUrl(): string
    {
        if ($callbackUrl = $this->config()->get('callback_url')) {
            return $callbackUrl;
        }

        throw new InvalidArgumentException('Không tìm thấy thuộc tính callback_url. Xem thêm tại: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html#tao-url-thanh-toan');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getMessage(): string
    {
        if ($message = $this->config()->get('message')) {
            return $message;
        }

        throw new InvalidArgumentException('Không tìm thấy thuộc tính message. Xem thêm tại: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html#tao-url-thanh-toan');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getTmnCode(): string
    {
        if ($tmnCode = $this->config()->get('tmn_code')) {
            return $tmnCode;
        }

        throw new InvalidArgumentException('Không tìm thấy thuộc tính tmn_code. Xem thêm tại: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html#tao-url-thanh-toan');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getAmount(): string|int
    {
        if ($amount = $this->config()->get('amount')) {
            return $amount;
        }

        throw new InvalidArgumentException('Không tìm thấy thuộc tính amount. Xem thêm tại: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html#tao-url-thanh-toan');
    }

    public function getBankCode(): mixed
    {
        return $this->config()->get('bank_code');
    }

    public function getCreateDate(): string
    {
        $createDate = $this->config()->get('create_date');
        if (empty($createDate)) {
            $createDate = new DateTime('now', $this->getTimezone());
        }
        if (is_string($createDate)) {
            return $createDate;
        }
        return $createDate->format('YmdHis');
    }

    public function getExpiredDate(): string
    {
        $expiredDate = $this->config()->get('expired_date');
        if (empty($expiredDate)) {
            // Thời gian tạo +5 phút
            $expiredDate = DateTime::createFromFormat('YmdHis', $this->getCreateDate())
                ->modify('+5 minutes');
        }
        if (is_string($expiredDate)) {
            return $expiredDate;
        }
        return $expiredDate->format('YmdHis');
    }

    public function getIpAddress(): string
    {
        if ($ipAddress = $this->config()->get('ip_address')) {
            return $ipAddress;
        }

        throw new InvalidArgumentException('Không tìm thấy thuộc tính ip_address. Xem thêm tại: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html#tao-url-thanh-toan');
    }

    public function getLocale(): string
    {
        return $this->config()->get('locale', 'vn');
    }
}
