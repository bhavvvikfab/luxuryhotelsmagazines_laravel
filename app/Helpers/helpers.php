<?php 
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Token;
use Stripe\SetupIntent;
use Stripe\PaymentMethod;
use App\Models\PaymentModel;
use App\Models\User;

// use PayPal\Api\Amount;
// use PayPal\Api\Details;
// use PayPal\Api\Item;
// use PayPal\Api\ItemList;
// use PayPal\Api\Payer;
// use PayPal\Api\Payment;
// use PayPal\Api\RedirectUrls;
// use PayPal\Api\Transaction;
// use PayPal\Rest\ApiContext;
// use PayPal\Auth\OAuthTokenCredential;
// use PayPal\Auth\OAuthTokenCredential;
// use PayPal\Rest\ApiContext;
// use PayPal\Api\Payer;
// use PayPal\Api\Amount;
// use PayPal\Api\Transaction;
// use PayPal\Api\Payment;
// use PayPal\Api\CreditCard;
// use PayPal\Api\Amount;
// use PayPal\Api\Details;
// use PayPal\Api\Item;
// use PayPal\Api\ItemList;
// use PayPal\Api\Payer;
// use PayPal\Api\Payment;
// use PayPal\Api\RedirectUrls;
// use PayPal\Api\Transaction;
// use PayPal\Auth\OAuthTokenCredential;
// use PayPal\Rest\ApiContext;
// use PayPal\Core\PayPalHttpConfig;
// use PayPal\Core\PayPalHttpClient;
// use PayPal\PayPalAPI\DoDirectPaymentReq;
// use PayPal\EBLBaseComponents\DoDirectPaymentRequestDetailsType;
// use PayPal\Service\PayPalAPIInterfaceServiceService;
// use PayPal\CoreComponentTypes\BasicAmountType;
// use PayPal\CoreComponentTypes\DoDirectPaymentRequestType;
// use PayPal\CoreComponentTypes\CreditCardDetailsType;


use Illuminate\Support\Facades\Log;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\CreditCard;

// helpers.php

if (!function_exists('exampleHelper')) {
    function exampleHelper()
    {
        return 'This is an example helper function.';
    }

    function paypalSubscription(){
        return 'This is an example helper function.';
    }
}

if (!function_exists('stripeSinglePayment')) {
    function stripeSinglePayment($card_details)
    {
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

}

if (!function_exists('stripeSubscriptionPayment')) {
    function stripeSubscriptionPayment($card_details)
    {
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
 function getBaseUrl()
{
    return url('/');
}
if (!function_exists('paypalSinglePayment')) {
    function paypalSinglePayment($card_details)
  {
    try {
        // Set up PayPal API credentials
        $paypalConfig = config('services.paypal');
        $apiContext = new ApiContext(
            new OAuthTokenCredential($paypalConfig['client_id'], $paypalConfig['secret'])
        );

        $cardDetails = [
            'number' => $card_details['number'], // Replace with a valid card number
            'exp_month' => $card_details['exp_month'],
            'exp_year' => $card_details['exp_year'],
            'cvc' => $card_details['cvc']
        ];

        // Create a credit card object
        $card = new CreditCard();
        $card->setType("visa");
        $card->setNumber($card_details['number']);
        $card->setExpireMonth($card_details['exp_month']);
        $card->setExpireYear($card_details['exp_year']);
        $card->setCvv2($card_details['cvc']);
        // $card->setFirstName($card_details['first_name']);
        // $card->setLastName($card_details['last_name']);

        $payer = new Payer();
        $payer->setPaymentMethod('credit_card')
            ->setFundingInstruments([$card]);

        $amount = new Amount();
        $amount->setCurrency('USD') // Replace with your currency code
               ->setTotal($card_details['amount']);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setDescription('Payment for your product or service');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(url('/paypal/success'))
                     ->setCancelUrl(url('/paypal/cancel'));

        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls);

        $payment->create($apiContext);

        // Save payment details in your database
        // $paymentModel = new PaymentModel();
        // //$paymentModel->user_id = $paypal_details['user_id'];
        // $paymentModel->amount = $card_details['amount'];
        // $paymentModel->payment_method = 'paypal';
        // $paymentModel->payment_type = 'sale';
        // $paymentModel->paypal_payment_id = $payment->getId();
        // $paymentModel->payment_status = 'pending'; // You may update this later based on IPN or Webhook

        // $paymentModel->save();

        // Redirect the user to PayPal for payment approval
        $approvalUrl = $payment->getApprovalLink();
        $response = json_encode(['success' => true, 'approval_url' => $approvalUrl, 'payment_details' => $paymentModel]);
    } catch (\Exception $e) {
        // Handle payment failure
        Log::error($e->getMessage());
        $response = json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    return $response;
}
// function paypalSinglePayment($card_details)
// {

   
//         // require_once(ROOT . DS . 'vendor' . DS . "autoload.php");       

//         // integrate paypal 
//         // $apiContext = new \PayPal\Rest\ApiContext(
//         //   new \PayPal\Auth\OAuthTokenCredential(
//         //     config('services.paypal.client_id'),
//         //     config('services.paypal.secret')
//         //     )
//         // );

// //         $payer = new Payer();
// //         $payer->setPaymentMethod("paypal");
// //         //Itemized information (Optional) Lets you specify item wise information

// //         $item1 = new Item();
// //         $item1->setName('Ground Coffee 40 oz')
// //             ->setCurrency('USD')
// //             ->setQuantity(1)
// //             ->setPrice(17);

// //         $itemList = new ItemList();
// //         $itemList->setItems(array($item1));

// //         $details = new Details();
// //         $details->setShipping(1)
// //             ->setTax(2)
// //             ->setSubtotal(17);

// //         $amount = new Amount();
// //         $amount->setCurrency("USD")
// //             ->setTotal(20)
// //             ->setDetails($details);

// //         $transaction = new Transaction();
// //         $transaction->setAmount($amount)
// //             ->setItemList($itemList)
// //             ->setDescription("Payment description")
// //             ->setInvoiceNumber(uniqid());

// //         //$baseUrl = $this->getBaseUrl();

// //         $redirectUrls = new RedirectUrls();
// //         $redirectUrls->setReturnUrl(getBaseUrl())
// //             ->setCancelUrl(getBaseUrl());

// //         $payment = new Payment();


// //         $payment->setIntent("order")
// //             ->setPayer($payer)
// //             ->setRedirectUrls($redirectUrls)
// //             ->setTransactions(array($transaction));
// //         //For Sample Purposes Only.

// //        $request = clone $payment;
// //         try {
      
// //             $payment->create($apiContext);
// //            $approvalUrl = $payment->getApprovalLink();
// //            $transactionId = $payment->getId();
// //            echo $transactionId;
// //            $paymentStatus = $payment->getState();

// // echo "Payment Status: $paymentStatus";
// //             // Get the payment approval URL and redirect the user
// //             // $approvalUrl = $payment->getApprovalLink();
// //             // return redirect($approvalUrl);
// //         } catch (\Exception $ex) {
// //             // Handle exceptions
// //             echo "shsd";
// //             dd($ex->getMessage());
// //         }
    
//         // try {
//         //     $paymentdetail = $payment->create($apiContext);
//         //     echo '<pre>';
//         //     print_r($paymentdetail);
//         //     echo '</pre>';

//         //     die('inside try ');
//         // } catch (Exception $ex) {
//         //     //NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY

//         //     ResultPrinter::printError("Created Payment Order Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
//         //     die('Inside catch');
//         // }
//         //Get redirect url
//         //The API response provides the url that you must redirect the buyer to. Retrieve the url from the $payment->getApprovalLink() method

//         // $approvalUrl = $payment->getApprovalLink();
//         //NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY

//         //  ResultPrinter::printResult("Created Payment Order Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);

//       //  return $payment;    
    

// /////
//     $apiContext = new ApiContext(
//         new OAuthTokenCredential(
//             config('services.paypal.client_id'),
//             config('services.paypal.secret')
//         )
//     );

//     // $apiContext->setConfig([
//     //     'mode' => config('services.paypal.mode'),
//     // ]);

//     // Create a credit card object
//     $card = new CreditCard();
//     $card->setType('visa')
//         ->setNumber('4242424242424242')
//         ->setExpireMonth('12')
//         ->setExpireYear('2028')
//         ->setCvv2('123');

//     // Create a payer object
//     $payer = new Payer();
//     $payer->setPaymentMethod('credit_card')
//         ->setFundingInstruments([['credit_card' => $card]]);

//     // Create an amount object
//     $amount = new Amount();
//     $amount->setCurrency('USD')
//         ->setTotal('10.00');

//     // Create a transaction object
//     $transaction = new Transaction();
//     $transaction->setAmount($amount)
//         ->setDescription('Payment description');

//         $redirectUrls = new RedirectUrls();
//         $redirectUrls->setReturnUrl('https://example.com/your-return-url')
//              ->setCancelUrl('https://example.com/your-cancel-url');
//     // Create a payment object
//     $payment = new Payment();
//     $payment->setIntent('sale')
//         ->setPayer($payer)
//         ->setTransactions([$transaction])
//         ->setRedirectUrls($redirectUrls);

//     try {
      
//         $payment->create($apiContext);
//         $approvalUrl = $payment->getApprovalLink();
      
//         // Get the payment approval URL and redirect the user
//         // $approvalUrl = $payment->getApprovalLink();
//         // return redirect($approvalUrl);
//     } catch (\Exception $ex) {
//         // Handle exceptions
//         // echo "shsd";
//         dd($ex->getMessage());
//     }

//     // $apiCredentials = new \PayPal\Auth\OAuthTokenCredential(
//     //     config('services.paypal.api_username'),
//     //     config('services.paypal.api_password'),
//     //     config('services.paypal.api_signature')
//     // );

//     // $apiContext = new \PayPal\Rest\ApiContext($apiCredentials);
//     // $apiContext->setConfig([
//     //     'mode' => config('services.paypal.mode'),
//     // ]);

//     // $cardDetails = new \PayPal\PayPalAPI\CreditCardDetailsType();
//     // $cardDetails->CreditCardNumber = '4111111111111111';
//     // $cardDetails->CreditCardType = 'VISA';
//     // $cardDetails->ExpMonth = '12';
//     // $cardDetails->ExpYear = '2024';
//     // $cardDetails->CVV2 = '123';

//     // $paymentDetails = new BasicAmountType();
//     // $paymentDetails->currencyID = 'USD';
//     // $paymentDetails->value = '10.00';

//     // $doDirectPaymentRequestDetails = new DoDirectPaymentRequestDetailsType();
//     // $doDirectPaymentRequestDetails->CreditCard = $cardDetails;
//     // $doDirectPaymentRequestDetails->PaymentDetails = $paymentDetails;

//     // $doDirectPaymentRequest = new DoDirectPaymentRequestType();
//     // $doDirectPaymentRequest->DoDirectPaymentRequestDetails = $doDirectPaymentRequestDetails;

//     // $doDirectPaymentReq = new DoDirectPaymentReq();
//     // $doDirectPaymentReq->DoDirectPaymentRequest = $doDirectPaymentRequest;

//     // try {
//     //     $response = $apiContext->execute($doDirectPaymentReq);
//     //     // Handle the response
//     //     dd($response);
//     // } catch (\Exception $ex) {
//     //     // Handle exceptions
//     //     dd($ex->getMessage());
//     // }

// //    try {
// //         // $apiContext = new \PayPal\Rest\ApiContext(
// //         //     new \PayPal\Auth\OAuthTokenCredential(
// //         //         config('services.paypal.client_id'),
// //         //         config('services.paypal.secret')
// //         //     )
// //         // );
        


// // // Set the custom API URL

// //         $apiContext = new ApiContext(
// //             new OAuthTokenCredential(
// //                 config('paypal.client_id'),
// //                 config('paypal.secret')
// //             )
// //         );

// //         $apiContext->setConfig(config('services.paypal'));
       
// //         // Set payer details
// //         $payer = new Payer();
// //         $payer->setPaymentMethod("paypal");
      
// //         // Set item details
// //         $item = new Item();
// //         $item->setName("Your Product or Service")
// //             ->setCurrency("USD")
// //             ->setQuantity(1)
// //             ->setPrice($card_details['amount']);
            
// //         $itemList = new ItemList();
// //         $itemList->setItems([$item]);
        
// //         // Set payment details
// //         $amount = new Amount();
// //         $amount->setCurrency("USD")
// //             ->setTotal($card_details['amount']);
           
// //         $transaction = new Transaction();
       
// //         $transaction->setAmount($amount)
// //             ->setItemList($itemList)
// //             ->setDescription("Payment for your product or service");
            
// //         // Set redirect URLs
// //         // $redirectUrls = new RedirectUrls();
// //         // $redirectUrls->setReturnUrl("YOUR_RETURN_URL")
// //         //     ->setCancelUrl("YOUR_CANCEL_URL");
        
// //         // Create payment
// //         $payment = new Payment();
      
// //         // $payment->setIntent("sale")
// //         //     ->setPayer($payer)
// //         //     ->setTransactions([$transaction]);
// //         $payment->setIntent("sale");
// //         $payment->setPayer($payer);
// //         $payment->setTransactions([$transaction]);
     
// //         // $payment->setPayer($payer);
// //         // $payment->setTransactions([$transaction]);
// //             // ->setRedirectUrls($redirectUrls);
           
// //         // Create payment and get approval link
// //         $payment->create($apiContext);
      
// //         // Get approval link
// //        // $approvalUrl = $payment->getApprovalLink();

// //         // You can redirect the user to $approvalUrl to complete the payment

// //         // Handle successful payment
// //         $response = json_encode(['success' => true, 'approval_url' => ""]);
// //     } catch (\Exception $e) {
// //         // Handle payment failure
// //        $response = json_encode(['success' => false, 'message' => $e->getMessage()]);
// //     }

// //     return $response;
//  }
}