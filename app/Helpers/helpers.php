<?php 
// helpers.php


namespace App\Helpers;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Token;
use Stripe\SetupIntent;
use Stripe\PaymentMethod;
use App\Models\PaymentModel;
use App\Models\User;
class Helpers
{
    public static function processPayment($amount)
    {
        // Your payment processing logic here
        return "Payment processed for $amount";
    }

    public static function stripeSinglePayment($card_details){
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $cardDetails = [
                'number' => $card_details['number'], // Replace with a valid card number
                'exp_month' => $card_details['exp_month'],
                'exp_year' => $card_details['exp_year'],
                'cvc' => $card_details['cvc']
            ];
            $token = \Stripe\Token::create([
                'card' => $cardDetails,
            ]);
        
            $tokenId = $token->id;

            $charge = \Stripe\Charge::create([
                'amount' => $card_details['amount'], // amount in cents
                'currency' => 'usd',
                'source' => $tokenId,
                'description' => 'Payment for your product or service',
            ]);

            // $transactionId = $charge->id;

                $payment = new PaymentModel();
                $payment->user_id = $card_details['user_id'];
                $payment->amount = $charge->amount;
                $payment->payment_method = "stripe";
                $payment->payment_type = "charge";
                $payment->charge_subscription_id = $charge->id;
                $payment->transaction_id = $charge->balance_transaction;
                $payment->payment_status = $charge->status;

                $payment->save();
    
            // Handle successful payment
            $response =  json_encode(['success' => true, 'message' => 'Payment successful']);
        } catch (\Exception $e) {
            // Handle payment failure
            $response = json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

        return $response;
    }

    public static  function stripeSubscriptionPayment($card_details){
        try {
            // Set the Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Create a token for the card
            $cardDetails = [
                'number' => $card_details['number'],
                'exp_month' => $card_details['exp_month'],
                'exp_year' => $card_details['exp_year'],
                'cvc' => $card_details['cvc']
            ];


            $token = \Stripe\Token::create([
                'card' => $cardDetails,
            ]);

            $tokenId = $token->id;

            $product_id = 'price_1OaBX6H3z9jiRVAKQkQ9h7KE';

            $get_stripe_cust_data = User::find($card_details['user_id']);
            
            if(!empty($get_stripe_cust_data) && !empty($get_stripe_cust_data['stripe_customer_id'])){
                $customer = \Stripe\Customer::retrieve($get_stripe_cust_data['stripe_customer_id']);
              
             }else{
                // Create a customer with the token
                $customer = \Stripe\Customer::create([
                    'name' => "Demo",
                    'description' => 'test description',
                    'source' => $tokenId,
                    'email' => "demo@gmail.com", // Replace with the customer's email
                ]);
            }
            $pmt_method = \Stripe\PaymentMethod::create([
                'type'=>'card',
                'card'=> [
                    'number' => $card_details['number'],
                    'exp_month' => $card_details['exp_month'],
                    'exp_year'=> $card_details['exp_year'],
                    'cvc' => $card_details['cvc']
                    ]
                ]);

                $attachmethod = \Stripe\PaymentMethod::retrieve($pmt_method->id);
                $attachmethod->attach(['customer' => $customer->id]);

        
               
            $intent = \Stripe\SetupIntent::create([
                    'payment_method_types'=>['card'],
                    'payment_method' => $pmt_method->id,
                    'customer' => $customer->id,
                    'confirm' => true,
                    'usage' => "off_session",
            ]);

           

            // Create a subscription for the customer
            $subscription = \Stripe\Subscription::create([
                'customer' => $customer->id,
                'off_session' => true,
                'default_payment_method' => $pmt_method->id,
                'items' => [
                    ['price' => $product_id], // Replace with the actual price ID from your Stripe dashboard
                ],
            ]);
           
           
            // Save the subscription information in your database
            $payment = new PaymentModel();
            $payment->user_id = $card_details['user_id'];
            $payment->amount = $subscription->items->data[0]->price->unit_amount / 100;
            // $payment->amount = $subscription->items->data[0]->price->unit_amount;
            $payment->payment_method = "stripe";
            $payment->payment_type = "subscription";
            $payment->charge_subscription_id = $subscription->id;
            $payment->start_date = date('Y-m-d H:i:s', $subscription->current_period_start);
            $payment->end_date = date('Y-m-d H:i:s', $subscription->current_period_end);
            $payment->transaction_id = $subscription->id;
            $payment->payment_status = $subscription->status;

            $payment->save();


          
            $userdt = User::find($card_details['user_id']);
            $userdt->stripe_customer_id = $subscription->customer;
            $userdt->save();

            // Handle successful subscription
            $response = json_encode(['success' => true, 'message' => 'Subscription successful']);
        } catch (\Exception $e) {
            // Handle subscription failure
            $response = json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

        return $response;
    }
}


// if (!function_exists('exampleHelper')) {
//     function exampleHelper()
//     {
//         return 'This is an example helper function.';
//     }

//     function paypalSubscription(){
//         return 'This is an example helper function.';
//     }
// }

// if (!function_exists('stripeSinglePayment')) {
//     function stripeSinglePayment($card_details)
//     {
//         try {
//             Stripe::setApiKey(config('services.stripe.secret'));
//             $cardDetails = [
//                 'number' => $card_details['number'], // Replace with a valid card number
//                 'exp_month' => $card_details['exp_month'],
//                 'exp_year' => $card_details['exp_year'],
//                 'cvc' => $card_details['cvc']
//             ];
//             $token = \Stripe\Token::create([
//                 'card' => $cardDetails,
//             ]);
        
//             $tokenId = $token->id;

//             $charge = \Stripe\Charge::create([
//                 'amount' => $card_details['amount'], // amount in cents
//                 'currency' => 'usd',
//                 'source' => $tokenId,
//                 'description' => 'Payment for your product or service',
//             ]);

//             // $transactionId = $charge->id;

//                 $payment = new PaymentModel();
//                 $payment->user_id = $card_details['user_id'];
//                 $payment->amount = $charge->amount;
//                 $payment->payment_method = "stripe";
//                 $payment->payment_type = "charge";
//                 $payment->charge_subscription_id = $charge->id;
//                 $payment->transaction_id = $charge->balance_transaction;
//                 $payment->payment_status = $charge->status;

//                 $payment->save();
    
//             // Handle successful payment
//             $response =  json_encode(['success' => true, 'message' => 'Payment successful']);
//         } catch (\Exception $e) {
//             // Handle payment failure
//             $response = json_encode(['success' => false, 'message' => $e->getMessage()]);
//         }

//         return $response;
//     }

// }

// if (!function_exists('stripeSubscriptionPayment')) {
//     function stripeSubscriptionPayment($card_details)
//     {
//         try {
//             // Set the Stripe API key
//             Stripe::setApiKey(config('services.stripe.secret'));

//             // Create a token for the card
//             $cardDetails = [
//                 'number' => $card_details['number'],
//                 'exp_month' => $card_details['exp_month'],
//                 'exp_year' => $card_details['exp_year'],
//                 'cvc' => $card_details['cvc']
//             ];


//             $token = \Stripe\Token::create([
//                 'card' => $cardDetails,
//             ]);

//             $tokenId = $token->id;

//             $product_id = 'price_1OaBX6H3z9jiRVAKQkQ9h7KE';

//             $get_stripe_cust_data = User::find($card_details['user_id']);
            
//             if(!empty($get_stripe_cust_data) && !empty($get_stripe_cust_data['stripe_customer_id'])){
//                 $customer = \Stripe\Customer::retrieve($get_stripe_cust_data['stripe_customer_id']);
              
//              }else{
//                 // Create a customer with the token
//                 $customer = \Stripe\Customer::create([
//                     'name' => "Demo",
//                     'description' => 'test description',
//                     'source' => $tokenId,
//                     'email' => "demo@gmail.com", // Replace with the customer's email
//                 ]);
//             }
//             $pmt_method = \Stripe\PaymentMethod::create([
//                 'type'=>'card',
//                 'card'=> [
//                     'number' => $card_details['number'],
//                     'exp_month' => $card_details['exp_month'],
//                     'exp_year'=> $card_details['exp_year'],
//                     'cvc' => $card_details['cvc']
//                     ]
//                 ]);

//                 $attachmethod = \Stripe\PaymentMethod::retrieve($pmt_method->id);
//                 $attachmethod->attach(['customer' => $customer->id]);

        
               
//             $intent = \Stripe\SetupIntent::create([
//                     'payment_method_types'=>['card'],
//                     'payment_method' => $pmt_method->id,
//                     'customer' => $customer->id,
//                     'confirm' => true,
//                     'usage' => "off_session",
//             ]);

           

//             // Create a subscription for the customer
//             $subscription = \Stripe\Subscription::create([
//                 'customer' => $customer->id,
//                 'off_session' => true,
//                 'default_payment_method' => $pmt_method->id,
//                 'items' => [
//                     ['price' => $product_id], // Replace with the actual price ID from your Stripe dashboard
//                 ],
//             ]);
           
           
//             // Save the subscription information in your database
//             $payment = new PaymentModel();
//             $payment->user_id = $card_details['user_id'];
//             $payment->amount = $subscription->items->data[0]->price->unit_amount / 100;
//             // $payment->amount = $subscription->items->data[0]->price->unit_amount;
//             $payment->payment_method = "stripe";
//             $payment->payment_type = "subscription";
//             $payment->charge_subscription_id = $subscription->id;
//             $payment->start_date = date('Y-m-d H:i:s', $subscription->current_period_start);
//             $payment->end_date = date('Y-m-d H:i:s', $subscription->current_period_end);
//             $payment->transaction_id = $subscription->id;
//             $payment->payment_status = $subscription->status;

//             $payment->save();


          
//             $userdt = User::find($card_details['user_id']);
//             $userdt->stripe_customer_id = $subscription->customer;
//             $userdt->save();

//             // Handle successful subscription
//             $response = json_encode(['success' => true, 'message' => 'Subscription successful']);
//         } catch (\Exception $e) {
//             // Handle subscription failure
//             $response = json_encode(['success' => false, 'message' => $e->getMessage()]);
//         }

//         return $response;
//     }
// }