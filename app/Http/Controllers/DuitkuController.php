<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Duitku;

class DuitkuController extends Controller
{
    public function checkout()
    {
        $merchantCode = env('DUITKU_MERCHANT_CODE');
        $merchantKey = env('DUITKU_API_KEY');

        $timestamp = round(microtime(true) * 1000); //in milisecond
        $paymentAmount = 40000;
        $merchantOrderId = time() . ''; // dari merchant, unique
        $productDetails = 'Test Pay with duitku';
        $email = 'test@test.com'; // email pelanggan merchant
        $phoneNumber = '08123456789'; // nomor tlp pelanggan merchant (opsional)
        $additionalParam = ''; // opsional
        $merchantUserInfo = ''; // opsional
        $customerVaName = 'John Doe'; // menampilkan nama pelanggan pada tampilan konfirmasi bank
        $callbackUrl = 'http://example.com/api-pop/backend/callback.php'; // url untuk callback
        $returnUrl = 'http://example.com/api-pop/backend/redirect.php'; //'http://example.com/return'; // url untuk redirect
        $expiryPeriod = 10; // untuk menentukan waktu kedaluarsa dalam menit
        $signature = hash('sha256', $merchantCode . $timestamp . $merchantKey);

        // Detail pelanggan
        $firstName = "John";
        $lastName = "Doe";

        // Alamat
        $alamat = "Jl. Kembangan Raya";
        $city = "Jakarta";
        $postalCode = "11530";
        $countryCode = "ID";

        $address = array(
            'firstName' => $firstName,
            'lastName' => $lastName,
            'address' => $alamat,
            'city' => $city,
            'postalCode' => $postalCode,
            'phone' => $phoneNumber,
            'countryCode' => $countryCode
        );

        $customerDetail = array(
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'billingAddress' => $address,
            'shippingAddress' => $address
        );


        $item1 = array(
            'name' => 'Test Item 1',
            'price' => 10000,
            'quantity' => 1
        );

        $item2 = array(
            'name' => 'Test Item 2',
            'price' => 30000,
            'quantity' => 3
        );

        $itemDetails = array(
            $item1, $item2
        );

        $params = array(
            'paymentAmount' => $paymentAmount,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => $productDetails,
            'additionalParam' => $additionalParam,
            'merchantUserInfo' => $merchantUserInfo,
            'customerVaName' => $customerVaName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'itemDetails' => $itemDetails,
            'customerDetail' => $customerDetail,
            'callbackUrl' => $callbackUrl,
            'returnUrl' => $returnUrl,
            'expiryPeriod' => $expiryPeriod
            //'paymentMethod' => $paymentMethod
        );

        $params_string = json_encode($params);
        //echo $params_string;
        $url = env('DUITKU_BASE_URL' . '/merchant/createinvoice'); // Sandbox

        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($params_string),
                'x-duitku-signature:' . $signature,
                'x-duitku-timestamp:' . $timestamp,
                'x-duitku-merchantcode:' . $merchantCode
            )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        //execute post
        $request = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode == 200) {
            $result = json_decode($request, true);
            return redirect($result['paymentUrl']);
        } else {
            echo $request;
        }
    }
}
