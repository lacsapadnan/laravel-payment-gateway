<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TripayController extends Controller
{
    public function channel()
    {
        $url = env('TRIPAY_BASE_URL') . '/merchant/payment-channel';
        $apiKey = env('TRIPAY_API_KEY');
        $headers = [
            'Authorization: Bearer ' . $apiKey,
        ];

        // curl
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $listPayment = json_decode($response, true);
            // array list payment
            $listPayment = $listPayment['data'];
        }
        return view('tripay', compact('listPayment'));
    }

    public function checkout()
    {
        $url = env('TRIPAY_BASE_URL') . '/transaction/create';
        $apiKey = env('TRIPAY_API_KEY');
        $privateKey = 'qik10-X5bel-Gpdz9-WXeFA-LFqh7';
        $merchantCode = 'T0891';
        $merchantRef  = 'INV-100021';
        $amount       = 1000000;
        $headers = [
            'Authorization: Bearer ' . $apiKey,
        ];

        $data = [
            'method' => 'QRIS2',
            'merchant_ref' => $merchantRef,
            'amount' => $amount,
            'customer_name' => 'John Doe',
            'customer_email' => 'johndoe@mail.com',
            'customer_phone' => '081234567890',
            'order_items'    => [
                [
                    'sku'         => 'FB-06',
                    'name'        => 'Nama Produk 1',
                    'price'       => 500000,
                    'quantity'    => 1,
                    'product_url' => 'https://tokokamu.com/product/nama-produk-1',
                    'image_url'   => 'https://tokokamu.com/product/nama-produk-1.jpg',
                ],
                [
                    'sku'         => 'FB-07',
                    'name'        => 'Nama Produk 2',
                    'price'       => 500000,
                    'quantity'    => 1,
                    'product_url' => 'https://tokokamu.com/product/nama-produk-2',
                    'image_url'   => 'https://tokokamu.com/product/nama-produk-2.jpg',
                ]
            ],
            'return_url' => 'https://google.com',
            'expired_time' => (time() + (24 * 60 * 60)),
            'signature' => hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey)
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        // return redirect to checkout url from response
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);
            return redirect($response['data']['checkout_url']);
        }
    }

    public function transaction()
    {
        $url = env('TRIPAY_BASE_URL') . '/merchant/transactions';
        $apiKey = env('TRIPAY_API_KEY');
        $headers = [
            'Authorization: Bearer ' . $apiKey,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);
            $listTransaction = $response['data'];
            dd($listTransaction);
        }
    }
}
