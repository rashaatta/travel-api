<?php
namespace App\Payment\Gateways;

interface PaymentGatewayInterface {
    public function createSession(array $data): object;
    public function getSession(string $transactionId): object;
}
