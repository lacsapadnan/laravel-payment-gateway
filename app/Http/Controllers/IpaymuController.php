<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IpaymuController extends Controller
{
    public function checkout()
    {
        $va = env('IPAYMU_VIRTUAL_ACCOUNT');
        $secret = env('IPAYMU_API_KEY');
        $method = 'POST';

        $url = env('IPAYMU_BASE_URL');
        $method = 'POST';

        //Request Body//
        $body['product']    = array('headset', 'softcase');
        $body['qty']        = array('1', '3');
        $body['price']      = array('100000', '20000');
        $body['returnUrl']  = 'https://google.com';
        $body['cancelUrl']  = 'https://google.com';
        $body['notifyUrl']  = 'https://google.com';
        $body['referenceId'] = '1234'; //your reference id
        $body['buyerName']  = 'John Doe';
        $body['buyerEmail'] = 'johndoe@mail.com';
        $body['buyerPhone'] = '08123456789';
        $body['pickupArea'] = '80117';
        $body['pickupAddress'] = 'Jakarta';


        //Generate Signature
        // *Don't change this
        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $secret;
        $signature    = hash_hmac('sha256', $stringToSign, $secret);
        $timestamp    = Date('YmdHis');
        //End Generate Signature


        $ch = curl_init($url);

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);

        if ($err) {
            echo $err;
        } else {

            //Response
            $ret = json_decode($ret);
            if ($ret->Status == 200) {
                $sessionId  = $ret->Data->SessionID;
                $url        =  $ret->Data->Url;
                return redirect($url);
            } else {
                echo $ret;
            }
            //End Response
        }
    }
}
