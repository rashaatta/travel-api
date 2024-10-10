<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TabbyWebhookController extends Controller
{

    public function createWebhook()
    {
        $apiKey = config('services.tabby.secret');

        // Define the webhook payload
        $payload = [
            'url' => 'https://af96-156-196-198-2.ngrok-free.app/api/v1/webhook/tabby', // URL to receive the webhook events (must be publicly accessible)
            'event_types' => [
                'payment.success',
                'payment.failed',
                'refund.processed',
            ],
            'is_test' => true
        ];

        // Send POST request to Tabby API to create the webhook
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.tabby.ai/api/v1/webhooks', $payload);

        // Check for errors
        if ($response->successful()) {
            // Webhook created successfully
            return response()->json([
                'message' => 'Webhook created successfully',
                'data' => $response->json(),
            ]);
        } else {
            // Check for specific error (invalid webhook URL)
            if ($response->status() == 400) {
                $error = $response->json();

                if (isset($error['message']) && str_contains($error['message'], 'Invalid webhook URL')) {
                    return response()->json([
                        'message' => 'Failed to create webhook: Invalid Webhook URL',
                        'error' => $error,
                    ], 400);
                }
            }

            // Log the error for debugging
            Log::error('Tabby Webhook Creation Error', ['response' => $response->json()]);

            return response()->json([
                'message' => 'Failed to create webhook',
                'error' => $response->json(),
            ], $response->status());
        }
    }
    public function createMultipleWebhooks()
    {
        $apiKey = config('services.tabby.secret');

        // Define an array of webhook configurations
        $webhooks = [
            [
                'url' => 'https://af96-156-196-198-2.ngrok-free.app/api/v1/webhook/tabby',
                'event_types' => ['payment.success'],
                'is_test' => true,
            ],
            [
                'url' => 'https://af96-156-196-198-2.ngrok-free.app/api/v1/webhook/tabby',
                'event_types' => ['payment.failed'],
                'is_test' => true,
            ],
            [
                'url' => 'https://af96-156-196-198-2.ngrok-free.app/api/v1/webhook/tabby',
                'event_types' => ['refund.processed'],
                'is_test' => true,
            ],
        ];

        foreach ($webhooks as $webhook) {
            // Send POST request to Tabby API to create each webhook
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post('https://api.tabby.ai/api/v1/webhooks', $webhook);

            // Check for errors
            if ($response->failed()) {
                Log::error('Failed to create Tabby Webhook', ['response' => $response->json()]);
                return response()->json(['message' => 'Failed to create webhooks', 'error' => $response->json()], $response->status());
            }
        }

        return response()->json(['message' => 'All webhooks created successfully']);
    }


    public function handleWebhook(Request $request)
    {
        try {
            // Verify webhook signature if provided
//            $signature = $request->header('X-Tabby-Signature');
//            $payload = $request->getContent();
//
//            // Optionally verify the signature using a secret
//            if (hash_hmac('sha256', $payload, config('services.tabby.webhook')) !== $signature) {
//                return response('Invalid signature', 403);
//            }

            // Process the webhook data (payment success, refunds, etc.)
            $webhookData = $request->all();

            // Log or process the event
            Log::info('Tabby Webhook received: ', $webhookData);
            Log::info('Tabby Webhook event: ', $webhookData['event']);
            $event = $webhookData['event'];
            // Perform necessary actions based on the event type
            switch ($event) {
                case 'payment.success':
                    return $this->handlePaymentSuccess($webhookData);
                case 'payment.failed':
                    return $this->handlePaymentFailed($webhookData);
                case 'refund.processed':
                    return $this->handleRefundProcessed($webhookData);
                default:
                    return response()->json(['message' => 'Unhandled event'], 200);
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Webhook handling error: ', ['message' => $e->getMessage()]);

            // Return an error response to avoid retry loops from Tabby
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    protected function handlePaymentSuccess($data)
    {
        // You can use the 'order_id' or 'buyer_email' to find the corresponding subscription/invoice
        $orderId = $data['order']['reference_id'];
        return response()->json(['message' => 'Payment successful'], 200);
    }

    protected function handlePaymentFailed($data)
    {
        // Handle payment failure
        $orderId = $data['order']['reference_id'];
        return response()->json(['message' => 'Payment failed'], 200);
    }

    protected function handleRefundProcessed($data)
    {
        // Handle refund processing
        $orderId = $data['order']['reference_id'];
        return response()->json(['message' => 'Refund processed'], 200);
    }
}
