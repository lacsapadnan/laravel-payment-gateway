<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Xendit\Invoice;
use Xendit\PaymentChannels;
use Xendit\Xendit;

class XenditController extends Controller
{
    public function paymentMethod()
    {
        Xendit::setApiKey(env('XENDIT_API_KEY'));

        $list = PaymentChannels::list();
        return view('xendit', compact('list'));
    }

    public function createInvoice()
    {
        Xendit::setApiKey(env('XENDIT_API_KEY'));
        $params = [
            'external_id' => 'demo_1293829382',
            'amount' => 50000,
            'description' => 'Invoice Demo #123',
            'invoice_duration' => 86400,
            'customer' => [
                'given_names' => 'John',
                'surname' => 'Doe',
                'email' => 'johndoe@example.com',
                'mobile_number' => '+6287774441111',
                'addresses' => [
                    [
                        'city' => 'Jakarta Selatan',
                        'country' => 'Indonesia',
                        'postal_code' => '12345',
                        'state' => 'Daerah Khusus Ibukota Jakarta',
                        'street_line1' => 'Jalan Makan',
                        'street_line2' => 'Kecamatan Kebayoran Baru'
                    ]
                ]
            ],
            'customer_notification_preference' => [
                'invoice_created' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ],
                'invoice_reminder' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ],
                'invoice_paid' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ],
                'invoice_expired' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ]
            ],
            'success_redirect_url' => 'https//www.google.com',
            'failure_redirect_url' => 'https//www.google.com',
            'currency' => 'IDR',
            'items' => [
                [
                    'name' => 'Air Conditioner',
                    'quantity' => 1,
                    'price' => 100000,
                    'category' => 'Electronic',
                    'url' => 'https=>//yourcompany.com/example_item'
                ]
            ],
            'fees' => [
                [
                    'type' => 'ADMIN',
                    'value' => 5000
                ]
            ]
        ];

        $invoice = Invoice::create($params);
        return redirect($invoice['invoice_url']);
    }

    public function allInvoice()
    {
        Xendit::setApiKey(env('XENDIT_API_KEY'));
        $invoice = Invoice::retrieveAll();
        dd($invoice);
    }

    public function callback()
    {
        $callbackToken = env('XENDIT_CALLBACK_TOKEN');
        // get headers
        $headers = getallheaders();
        $responseToken = isset($headers['x-callback-token']) ? $headers['x-callback-token'] : null;

        if ($responseToken !== $callbackToken) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid callback token'
            ], 401);
        }

        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        echo 'Callback received: ' . $data['event'] . ' for invoice ' . $data['data']['id'];
    }
}
