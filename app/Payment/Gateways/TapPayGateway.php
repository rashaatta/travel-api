<?php
namespace App\Payment\Gateways;

use Illuminate\Support\Facades\Http;

class TapPayGateway implements PaymentGatewayInterface {

    public $base_url = "https://api.tabby.ai/api/v2/";
    public function createSession($data): object
    {
        $body = $this->getConfig($data);

        $http = Http::withToken(config('services.tabby.key'))->baseUrl($this->base_url);

        $response = $http->post('checkout',$body);

        return $response->object();
    }

    public function getSession($transactionId):object
    {
        $http = Http::withToken(config('services.tabby.secret'))->baseUrl($this->base_url);

        $url = 'checkout/'.$transactionId;

        $response = $http->get($url);

        return $response->object();
    }

    public function getConfig($data):array
    {
        return [
            "payment" => [
                "amount" => $data['amount'],
                "currency" => $data['currency'],
                "description" =>  $data['description'],
                "buyer" => [
                    "phone" => $data['buyer_phone'],
                    "email" => $data['buyer_email'],
                    "name" => $data['full_name']
                ],
                "shipping_address" => [
                    "city" => $data['city'],
                    "address" =>  $data['address'],
                    "zip" => $data['zip'],
                ],
                "order" => [
                    "tax_amount" => "0.00",
                    "shipping_amount" => "0.00",
                    "discount_amount" => "0.00",
                    "updated_at" => now(),
                    "reference_id" => $data['order_id'],
                    "items" =>
                        $data['items']
                    ,
                ],
                "buyer_history" => [
                    "registered_since"=> $data['registered_since'],
                    "loyalty_level"=> $data['loyalty_level'],
                ],
            ],
            "lang" => app()->getLocale(),
            "merchant_code" => "your merchant_code",
            "merchant_urls" => [
                "success" => $data['success-url'],
                "cancel" => $data['cancel-url'],
                "failure" => $data['failure-url'],
            ]
        ];
    }
}
