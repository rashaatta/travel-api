<?php
namespace App\Payment;

use App\Payment\Gateways\PaymentGatewayInterface;

class PaymentService {
    protected $gateway;

    public function __construct(PaymentGatewayInterface $gateway) {
        $this->gateway = $gateway;
    }

    public function createSession(array $data): object {
        return $this->gateway->createSession($data);
    }

    public function getSession(string $transactionId): object {
        return $this->gateway->getSession($transactionId);
    }
}
