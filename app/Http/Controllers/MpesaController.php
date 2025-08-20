<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    private $shortcode;
    private $passkey;
    private $consumerKey;
    private $consumerSecret;
    private $callbackUrl;

    public function __construct()
    {
        $this->shortcode = env('MPESA_SHORTCODE', '174379'); // Default Daraja test shortcode
        $this->passkey = env('MPESA_PASSKEY');
        $this->consumerKey = env('MPESA_CONSUMER_KEY');
        $this->consumerSecret = env('MPESA_CONSUMER_SECRET');
        $this->callbackUrl = env('MPESA_CALLBACK_URL');
    }

    /**
     * ✅ Generate Access Token
     */
    private function getAccessToken()
    {
        try {
            $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)->get($url);

            Log::info("Access Token Response:", $response->json());

            if ($response->successful() && isset($response['access_token'])) {
                return $response['access_token'];
            }

            Log::error("Failed to get access token", ['response' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::error("Access Token Exception", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * ✅ STK Push (Customer payment request)
     */
    public function stkPush(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'phone' => 'required|regex:/^2547\d{8}$/'
        ]);

        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return response()->json(['error' => 'Failed to generate access token'], 500);
        }

        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $stkData = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => (int)$request->amount,
            'PartyA' => $request->phone,
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $request->phone,
            'CallBackURL' => $this->callbackUrl,
            'AccountReference' => 'Fruithub',
            'TransactionDesc' => 'Payment for fruits'
        ];

        try {
            $response = Http::withToken($accessToken)
                ->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', $stkData);

            Log::info("STK Push Request:", $stkData);
            Log::info("STK Push Response:", $response->json());

            return response()->json($response->json());

        } catch (\Exception $e) {
            Log::error("STK Push Exception", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'STK Push failed', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * ✅ Callback from M-Pesa after STK Push
     */
    public function callback(Request $request)
    {
        Log::info("M-Pesa Callback:", $request->all());

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Callback received successfully'
        ]);
    }
}
