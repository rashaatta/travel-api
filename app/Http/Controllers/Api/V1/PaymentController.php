<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function pay()
    {
        try {
            // Specify the payment gateway ('tabby', 'stcpay', 'tamara')
            $gateway = 'tabby'; // $request->input('gateway');

            // Initialize the correct payment service based on the gateway
            $paymentService = app("payment.$gateway");

            // Prepare the items collection for the order
            $items = collect();
            $items->push([
                'title' => 'title',
                'quantity' => 2,
                'unit_price' => 20,
                'category' => 'Clothes',
            ]);

            // Prepare the order data
            $order_data = [
                'amount' => 199,
                'currency' => 'SAR',
                'description' => 'description',
                'full_name' => 'ALi Omer',
                'buyer_phone' => '20121831835',
                'buyer_email' => 'card.success@tabby.ai',
                'address' => 'Saudi Riyadh',
                'city' => 'Riyadh',
                'zip' => '1234',
                'order_id' => '1234',
                'registered_since' => now(),
                'loyalty_level' => 0,
                'success-url' => route('travels.index'),
                'cancel-url' => route('travels.index'),
                'failure-url' => route('travels.index'),
                'items' => $items,
            ];

            // Create the payment session
            $payment = $paymentService->createSession($order_data);

            // Check if the payment session was created successfully
            if (!isset($payment->configuration->available_products->installments[0]->web_url)) {
                 throw new \Exception('Unable to create payment session. No redirect URL found.');
            }

            // Get the redirect URL
            $redirect_url = $payment->configuration->available_products->installments[0]->web_url;

            return response()->json(['redirect_url' => $redirect_url]);

        } catch (\Exception $e) {
            // Handle any general errors
            Log::error('Payment Error: ', ['gateway' => $gateway, 'error' => $e->getMessage(),]);

            return response()->json([
                'error' => 'An error occurred while processing the payment. Please try again.',
            ], 500);
        }
    }

}
