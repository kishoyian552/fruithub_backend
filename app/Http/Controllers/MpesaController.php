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

   // Access token generation for M-Pesa API
    private function getAccessToken()
    {
        try {
            $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';// M-Pesa sandbox URL for generating access token

            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)->get($url);// Make a GET request to the M-Pesa API with basic auth

            Log::info("Access Token Response:", $response->json());// Log the response from M-Pesa API

            if ($response->successful() && isset($response['access_token'])) {
                return $response['access_token'];
            }// If the response is successful and contains an access token, return it

            Log::error("Failed to get access token", ['response' => $response->body()]);
            return null;// If the response is not successful or does not contain an access token, log the error and return null

        } catch (\Exception $e) {
            Log::error("Access Token Exception", ['error' => $e->getMessage()]);// Log any exceptions that occur while trying to get the access token
            return null;
        }// Access token generation for M-Pesa API
    }//

    // stk push
    public function stkPush(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'phone' => 'required|regex:/^2547\d{8}$/'
        ]);// Validate the incoming request data

        $accessToken = $this->getAccessToken();// Get the access token

        if (!$accessToken) {
            return response()->json(['error' => 'Failed to generate access token'], 500);
        }// If access token generation fails, return an error response

        $timestamp = date('YmdHis');// Generate the current timestamp in the required format
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);// Generate the password for the STK push request

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
        ];// Prepare the data for the STK push request

        try {
            $response = Http::withToken($accessToken)
                ->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', $stkData);// Make a POST request to the M-Pesa STK push API with the access token and STK data

            Log::info("STK Push Request:", $stkData);
            Log::info("STK Push Response:", $response->json());// Log the STK push request data and response

            return response()->json($response->json());// Return the response from the M-Pesa API

        } catch (\Exception $e) {
            Log::error("STK Push Exception", ['error' => $e->getMessage()]);// Log any exceptions that occur during the STK push request
            return response()->json(['error' => 'STK Push failed', 'details' => $e->getMessage()], 500);
        }// Handle any exceptions that occur during the STK push request
    }

  //callback from mpesa
    public function callback(Request $request)
    {
        Log::info("M-Pesa Callback:", $request->all());// Log the callback data received from M-Pesa

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Callback received successfully'
        ]);// Acknowledge receipt of the callback
    }// stk push
}
