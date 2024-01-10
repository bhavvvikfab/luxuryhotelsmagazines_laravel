<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PaymentController extends Controller
{
    private $apiContext;

    private function getAccessToken($clientId, $secret, $mode)
    {
        $response = Http::asForm()->post("https://api.paypal.com/v1/oauth2/token", [
            'grant_type' => 'client_credentials',
        ])->withBasicAuth($clientId, $secret);

        if ($response->successful()) {
            return $response->json('access_token');
        } else {
            throw new \Exception('Failed to get access token from PayPal');
        }
    }
    
    private function makePayPalRequest($method, $url, $data, $headers)
    {
        $client = new Client();

        return $client->request($method, $url, [
            'json' => $data,
            'headers' => $headers,
        ]);
    }

    // $planId = 'P-7UD066132C2640912MWOSFGA'; // Replace with your actual plan ID
    public function payment(Request $request)
    {
        $cardDetails = $request->only(['card_number', 'expiration_month', 'expiration_year', 'cvv']);
        // Validate and sanitize card details here

        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');
        $mode = config('services.paypal.mode');

        $token = $this->getAccessToken($clientId, $secret, $mode);

        // Set up plan details
        $planId = 'your_plan_id'; // Replace with your actual plan ID

        $agreementDetails = [
            'name' => 'Subscription Agreement',
            'description' => 'Monthly subscription with card details',
            'start_date' => date('Y-m-d\TH:i:s\Z', strtotime('now')),
            'payer' => [
                'payment_method' => 'credit_card',
                'funding_instruments' => [['credit_card' => $cardDetails]],
            ],
            'plan' => ['id' => $planId],
        ];

        $response = $this->makePayPalRequest(
            'POST',
            'https://api.paypal.com/v1/payments/billing-agreements',
            $agreementDetails,
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ]
        );

        if ($response->getStatusCode() === 201) {
            $subscriptionId = json_decode($response->getBody(), true)['id'];
            return response()->json(['success' => true, 'subscription_id' => $subscriptionId]);
        } else {
            return response()->json(['error' => json_decode($response->getBody(), true)], $response->getStatusCode());
        }
    }
}
