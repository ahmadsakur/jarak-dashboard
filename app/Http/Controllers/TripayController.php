<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TripayController extends Controller
{
    //
    public function merchantChannel()
    {
        $apiKey = config('tripay.api_key');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/merchant/payment-channel',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error)
            return $error;

        return (json_decode($response)->data);
    }

    public function createPaymentRequest($method, $amount, $items, $user)
    {
        $apiKey       = config('tripay.api_key');
        $privateKey   = config('tripay.private_key');
        $merchantCode = config('tripay.merchant_id');
        $merchantRef  = 'JRK-' . time();

        $data = [
            'method'         => $method,
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $user['name'],
            'customer_email' => 'emailpelanggan@domain.com',
            'customer_phone' => $user['whatsappNumber'],
            'order_items'    => $items,
            'return_url'   => 'https://domainanda.com/redirect',
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey)
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);


        curl_close($curl);
        // this error somehow only cover network error, not the error from tripay
        if ($error)
            return response()->json([
                'status' => 500,
                'message' => 'failed',
                'data'   => $error
            ]);

        if(json_decode($response) == null)
            return response()->json([
                'status' => 500,
                'message' => 'Transaction Failed',
                'data'   => json_decode($response)
            ]
        );

        return response()->json([
            'status' => 200,
            "message" => "success",
            'data'   => json_decode($response)
        ]);
    }

    public function getOrderDetails($invoice)
    {

        $apiKey = config('tripay.api_key');
        $payload = ['reference'    => $invoice];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/transaction/detail?' . http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error)
            return $error;

        return (json_decode($response));
    }

    public function getOrderStatus($invoice)
    {
        // get Transaction status field as json, return status 200 if success, and 500 if failed
        $transaction = Transaction::where('transaction_id', $invoice)->first();
       
        // return a json response based on if the transaction is found or not
        if($transaction == null)
            return response()->json([
                'status' => 500,
                'message' => 'Transaction not found',
                'data'   => null
            ]);

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data'   => $transaction->transaction_status
        ]);
    }
}
