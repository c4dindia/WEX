<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT as FirebaseJWT;
use Illuminate\Support\Facades\Response;

class CardsAPIController extends Controller
{
    protected $privateKey = '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDQydgB7eNWaMnP
2EnVzwbVkhIuRTDqXa6qroCkn/XzvA+kEhINpupS38AWritVaU6hhEXeXaOi/NKm
rbxZUywW7US5i2OfxoOydQvIPgVBEeXUzA09IszZHYLUpxJcHzFflE6r8k0Uay/b
xUtipIukmLFfGRXA37P4ubfof2FI/1E47Nq0U8qwnkvTllBHEFfK+PL9UGSmswkP
MWxJ2Hdx2lmquP5ghxLCJNlDSTadz/nZcW3sSmS3qOmY++odM9Bui5bO4vR4IIA3
mxMfw8ArEbFnQmY271TxTgvqUZlsKh/Yp18H+yo8FTQOF25Q7WTdk1ZeVN2RYzmP
WaWYD2sBAgMBAAECggEAJORFYrHiBZamci9JfJoEHyCcTci0B/Vds5L58BzDnLmS
Ge+HeRoRNWuLyXYk+gaNekShA9WP4HDD1+fC7BWiKLrr9c0HYWf2RUYhOtSaoRDE
h4E2paLMh2CLiX+r4tMwKi9OZ1t8+yqlXjPSSi/A7Oh+Dsuj6FyetEg8krxPOFJy
u9NA0nNzgFJEU7FgOn+SSIR7IqLgtkdbEybQEHqmHFSvrkI1RCfp+u0BeEOdHkkq
meHyoLS0dGA4BGcF/aqZaZrZcvBjLtUIzqckHNITqfttSNYFSjGm0fWbQNhF2hsr
/Bp6mnRuBRscUp+QAsAnDyJuxwQYWnIiUq1yK/bfsQKBgQD97hSiryKP7OyFsqxu
q2XqKK5Rxs7dj4LfugsX8iVZLnHlJs2ozzZoB3N6iLH4689AA5oL94ym2XJZfvQ0
79U/HIiioYu8/IiarTzdJFiNWEZF3Jzr4qzkrRGXU7B1pOf8UY7rJ+Je7NKofVH0
NEngr7gAWkqvooeMPgu3r52gowKBgQDSfY78YcVNyBOmVufZYxN2vXdh1E5XlDlq
oC8DRPs37hnuN0xwZchDYdhFDnw9Y1bshpjw4s4TRBFTbRXkS1VkN6dzkBMUe4AH
alusehaP2UUdGY/athWr4DbbjZ7H7+mqCxCFzkF18TxS2DlZKldtjqce+zjLi2jh
S6ZtY/SsCwKBgQC62lOC9r7HC79sDnNGKIehC/hiIpocfTr/qGX7Y+rJuhyDU89o
FbpiSIFYPHhBxDvITh4wCiQMrtuvMXU/8u+HNlbw3VD3axiWQT4VOGaU880aIWGz
TiSNTTExmpqxpvwyNk2lir7PvRKyey0wNr3aQVNzpv1oSn/V2d5Dd03VYwKBgCdY
91CwiiyHGT1AA6k8fxf0RQvRny958t2+wesPixKifNBuH+jm7e/Cq6YFFi53knk2
lAJ7s2AgmkiJHM2HU5gfWzscFKUAjSdVt3tfezY6vFO7Qov0+8ocaMTUDXl8nKZ/
2P/aVZxRkyA9cUL8ykkdoJoHBk/uaJWtCZhrVgKPAoGAA10IAcA7dyXJoJ9RbWlt
gLsd3pSGmRl+ac4qxcv5KkY5A35oPhu5YFYGubNkCt6G7mqyhGP/mLik6vK1RmV/
G57HEWZ7zs9U5cKzTwJyhQ0PhFeMc0D6twWaNUPG6udi7zw3BGfuHFFTdEEJc3As
f6Q1EkqRu10nZroizeWFGR4=
-----END PRIVATE KEY-----
';
    public function getCardsList()
    {
        $accountData  = Account::where('user_id','=', Auth::user()->id)->first();
        $cards = Card::where('account_id', $accountData->id)
                    // ->where('card_role_type','expense')
                    ->orderBy('created_at','desc')
                    ->get();

        $noOfCards = count($cards);

        if($noOfCards > 0)
        {
            foreach($cards as $card){
                unset($card->id, $card->account_id, $card->card_number, $card->cvv2, $card->expiry_date, $card->card_password, $card->email,
                 $card->limits_daily_overall_purchase,
                 $card->limits_monthly_overall_purchase,
                 $card->updated_at,
                 $card->address1,
                 $card->address2,
                 $card->city,
                 $card->company_name,
                 $card->country_code,
                 $card->dispatch_method,
                 $card->first_name,
                 $card->last_name,
                 $card->phone,
                 $card->postal_code,
                 $card->contactless_enabled,
                 $card->withdrawal_enabled,
                 $card->internet_purchase_enabled,
                 $card->all_time_limits_enabled,
                 $card->receipts_reminder_enabled,
                 $card->instant_spend_update_enabled
                );
            }
        }

        return response()->json([
            'success' => true,
            'data' =>[
                'cards' => $cards,
                'number_of_cards' => $noOfCards,
            ]
        ],200);

    }

    public function blockCard($card_id)
    {
        $account_id = Account::where('user_id', Auth::user()->id)->first()->id;
        $card = Card::where('card_id', $card_id)->where('account_id',$account_id)->first();

        if($card == null){
            return response()->json([
                'success' => false,
                'error' => 'Card not found!'
            ],401);
        }

        $requestBody = ['block_type' => 'BlockedByCardholder',];
        $payload = [
            'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
            'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
            'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
            'sub'=> "api-request",
            'rbh'=> base64_encode(hash('sha256',json_encode($requestBody), true)),
            ];

        $token = FirebaseJWT::encode($payload, $this->privateKey, 'RS256');
        $url = 'https://api.wallester.eu/v1/cards/' . $card_id . '/block';
        $client = new Client();
        try {
            $response = $client->patch($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Product-Code' => 'NEUROSYNCBDUSD',
                    'X-Audit-User-Id' => '542564e4-2084-4554-9318-b29d65adebea',
                    'X-Audit-Source-Type' => 'Backend',
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorText = $errorResponse['error_text'] ?? 'An unknown error occurred';
            if($errorText == 'Card is closed')
            {
                $card->status = 'Closed';
                $card->save();
            }
            return response()->json([
                'success' => false,
                'error' => strtoupper($errorText)
            ],200);
        }

        // Handle successful response
        $responseData = json_decode($response->getBody()->getContents(), true);
        $cardData = $responseData['card'];
        $limits = $cardData['limits'];
        $secureSettings = $cardData['3d_secure_settings'];

        //update Card record
        $card->card_id = $cardData['id']; //generating a unique card_id
        $card->account_id = $account_id;
        $card->type = $cardData['type'];
        $card->card_name = $cardData['name'];
        $card->masked_card_number = $cardData['masked_card_number'];
        $card->expiry_date = $cardData['expiry_date'];
        $card->status = $cardData['status'];
        $card->email = $secureSettings['email']?? null;
        $card->mobile = $secureSettings['mobile']?? null;

        $card->limits_daily_contactless_purchase = $limits['daily_contactless_purchase'];
        $card->limits_daily_internet_purchase = $limits['daily_internet_purchase'];
        $card->limits_daily_overall_purchase = $limits['daily_overall_purchase'];
        $card->limits_daily_purchase = $limits['daily_purchase'];
        $card->limits_daily_withdrawal = $limits['daily_withdrawal'];
        $card->limits_monthly_contactless_purchase = $limits['monthly_contactless_purchase'];
        $card->limits_monthly_internet_purchase = $limits['monthly_internet_purchase'];
        $card->limits_monthly_overall_purchase = $limits['monthly_overall_purchase'] ;
        $card->limits_monthly_purchase = $limits['monthly_purchase'];
        $card->limits_monthly_withdrawal = $limits['monthly_withdrawal'];

        $card->limits_daily_contactless_purchase_used = $limits['daily_contactless_purchase_used'];
        $card->limits_daily_internet_purchase_used = $limits['daily_internet_purchase_used'];
        // $card->limits_daily_overall_purchase_used = $limits['daily_overall_purchase_used'];
        $card->limits_daily_purchase_used = $limits['daily_purchase_used'];
        $card->limits_daily_withdrawal_used = $limits['daily_withdrawal_used'];
        $card->limits_monthly_contactless_purchase_used = $limits['monthly_contactless_purchase_used'];
        $card->limits_monthly_internet_purchase_used = $limits['monthly_internet_purchase_used'];
        // $card->limits_monthly_overall_purchase_used = $limits['monthly_overall_purchase_used'] ;
        $card->limits_monthly_purchase_used = $limits['monthly_purchase_used'];
        $card->limits_monthly_withdrawal_used = $limits['monthly_withdrawal_used'];

        $card->limits_daily_contactless_purchase_available = $limits['daily_contactless_purchase_available'];
        $card->limits_daily_internet_purchase_available = $limits['daily_internet_purchase_available'];
        // $card->limits_daily_overall_purchase_available = $limits['daily_overall_purchase_available'];
        $card->limits_daily_purchase_available = $limits['daily_purchase_available'];
        $card->limits_daily_withdrawal_available = $limits['daily_withdrawal_available'];
        $card->limits_monthly_contactless_purchase_available = $limits['monthly_contactless_purchase_available'];
        $card->limits_monthly_internet_purchase_available = $limits['monthly_internet_purchase_available'];
        // $card->limits_monthly_overall_purchase_available = $limits['monthly_overall_purchase_available'] ;
        $card->limits_monthly_purchase_available = $limits['monthly_purchase_available'];
        $card->limits_monthly_withdrawal_available = $limits['monthly_withdrawal_available'];

        $card->save();

        unset($card->id, $card->account_id, $card->card_number, $card->cvv2, $card->expiry_date, $card->card_password, $card->email,
        $card->limits_daily_overall_purchase,
        $card->limits_monthly_overall_purchase,
        $card->updated_at,
        $card->address1,
        $card->address2,
        $card->city,
        $card->company_name,
        $card->country_code,
        $card->dispatch_method,
        $card->first_name,
        $card->last_name,
        $card->phone,
        $card->postal_code,
        $card->contactless_enabled,
        $card->withdrawal_enabled,
        $card->internet_purchase_enabled,
        $card->all_time_limits_enabled,
        $card->receipts_reminder_enabled,
        $card->instant_spend_update_enabled
       );

        return response()->json([
            'success' =>true,
            'message' => 'Card Blocked Successfully',
            // 'data' => $card
        ],200);
    }
    public function unblockCard($card_id)
    {
        $account_id = Account::where('user_id', Auth::user()->id)->first()->id;
        $card = Card::where('card_id', $card_id)->where('account_id',$account_id)->first();

        if($card == null){
            return response()->json([
                'success' => false,
                'error' => 'Card not found!'
            ],401);
        }

        $requestBody = ['block_type' => 'BlockedByCardholder',];
        $payload = [
            'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
            'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
            'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
            'sub'=> "api-request",
            'rbh'=> base64_encode(hash('sha256',json_encode($requestBody), true)),
            ];

        $token = FirebaseJWT::encode($payload, $this->privateKey, 'RS256');
        $url = 'https://api.wallester.eu/v1/cards/' . $card_id . '/unblock';
        $client = new Client();
        try {
            $response = $client->patch($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Product-Code' => 'NEUROSYNCBDUSD',
                    'X-Audit-User-Id' => '542564e4-2084-4554-9318-b29d65adebea',
                    'X-Audit-Source-Type' => 'Backend',
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorText = $errorResponse['error_text'] ?? 'An unknown error occurred';
            if($errorText == 'Card is closed')
            {
                $card->status = 'Closed';
                $card->save();
            }
            return response()->json([
                'success' => false,
                'error' => strtoupper($errorText)
            ],200);
        }

        // Handle successful response
        $responseData = json_decode($response->getBody()->getContents(), true);
        $cardData = $responseData['card'];
        $limits = $cardData['limits'];
        $secureSettings = $cardData['3d_secure_settings'];

        //update Card record
        $card->card_id = $cardData['id']; //generating a unique card_id
        $card->account_id = $account_id;
        $card->type = $cardData['type'];
        $card->card_name = $cardData['name'];
        $card->masked_card_number = $cardData['masked_card_number'];
        $card->expiry_date = $cardData['expiry_date'];
        $card->status = $cardData['status'];
        $card->email = $secureSettings['email']?? null;
        $card->mobile = $secureSettings['mobile']?? null;

        $card->limits_daily_contactless_purchase = $limits['daily_contactless_purchase'];
        $card->limits_daily_internet_purchase = $limits['daily_internet_purchase'];
        $card->limits_daily_overall_purchase = $limits['daily_overall_purchase'];
        $card->limits_daily_purchase = $limits['daily_purchase'];
        $card->limits_daily_withdrawal = $limits['daily_withdrawal'];
        $card->limits_monthly_contactless_purchase = $limits['monthly_contactless_purchase'];
        $card->limits_monthly_internet_purchase = $limits['monthly_internet_purchase'];
        $card->limits_monthly_overall_purchase = $limits['monthly_overall_purchase'] ;
        $card->limits_monthly_purchase = $limits['monthly_purchase'];
        $card->limits_monthly_withdrawal = $limits['monthly_withdrawal'];

        $card->limits_daily_contactless_purchase_used = $limits['daily_contactless_purchase_used'];
        $card->limits_daily_internet_purchase_used = $limits['daily_internet_purchase_used'];
        // $card->limits_daily_overall_purchase_used = $limits['daily_overall_purchase_used'];
        $card->limits_daily_purchase_used = $limits['daily_purchase_used'];
        $card->limits_daily_withdrawal_used = $limits['daily_withdrawal_used'];
        $card->limits_monthly_contactless_purchase_used = $limits['monthly_contactless_purchase_used'];
        $card->limits_monthly_internet_purchase_used = $limits['monthly_internet_purchase_used'];
        // $card->limits_monthly_overall_purchase_used = $limits['monthly_overall_purchase_used'] ;
        $card->limits_monthly_purchase_used = $limits['monthly_purchase_used'];
        $card->limits_monthly_withdrawal_used = $limits['monthly_withdrawal_used'];

        $card->limits_daily_contactless_purchase_available = $limits['daily_contactless_purchase_available'];
        $card->limits_daily_internet_purchase_available = $limits['daily_internet_purchase_available'];
        // $card->limits_daily_overall_purchase_available = $limits['daily_overall_purchase_available'];
        $card->limits_daily_purchase_available = $limits['daily_purchase_available'];
        $card->limits_daily_withdrawal_available = $limits['daily_withdrawal_available'];
        $card->limits_monthly_contactless_purchase_available = $limits['monthly_contactless_purchase_available'];
        $card->limits_monthly_internet_purchase_available = $limits['monthly_internet_purchase_available'];
        // $card->limits_monthly_overall_purchase_available = $limits['monthly_overall_purchase_available'] ;
        $card->limits_monthly_purchase_available = $limits['monthly_purchase_available'];
        $card->limits_monthly_withdrawal_available = $limits['monthly_withdrawal_available'];

        $card->save();

        unset($card->id, $card->account_id, $card->card_number, $card->cvv2, $card->expiry_date, $card->card_password, $card->email,
        $card->limits_daily_overall_purchase,
        $card->limits_monthly_overall_purchase,
        $card->updated_at,
        $card->address1,
        $card->address2,
        $card->city,
        $card->company_name,
        $card->country_code,
        $card->dispatch_method,
        $card->first_name,
        $card->last_name,
        $card->phone,
        $card->postal_code,
        $card->contactless_enabled,
        $card->withdrawal_enabled,
        $card->internet_purchase_enabled,
        $card->all_time_limits_enabled,
        $card->receipts_reminder_enabled,
        $card->instant_spend_update_enabled
       );

        return response()->json([
            'success' =>true,
            'message' => 'Card Unblocked Successfully',
            // 'data' => $card
        ],200);
    }
    public function closeCard($card_id)
    {
        $account_id = Account::where('user_id', Auth::user()->id)->first()->id;
        $card = Card::where('card_id', $card_id)->where('account_id',$account_id)->first();

        if($card == null){
            return response()->json([
                'success' => false,
                'error' => 'Card not found!'
            ],401);
        }

        $requestBody = ['close_reason' => 'ClosedByCardholder',];
        $payload = [
            'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
            'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
            'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
            'sub'=> "api-request",
            'rbh'=> base64_encode(hash('sha256',json_encode($requestBody), true)),
            ];

        $token = FirebaseJWT::encode($payload, $this->privateKey, 'RS256');
        $url = 'https://api.wallester.eu/v1/cards/' . $card_id . '/close';
        $client = new Client();
        try {
            $response = $client->patch($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Product-Code' => 'NEUROSYNCBDUSD',
                    'X-Audit-User-Id' => '542564e4-2084-4554-9318-b29d65adebea',
                    'X-Audit-Source-Type' => 'Backend',
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorText = $errorResponse['error_text'] ?? 'An unknown error occurred';
            if($errorText == 'Card is closed')
            {
                $card->status = 'Closed';
                $card->save();
            }
            return response()->json([
                'success' => false,
                'error' => strtoupper($errorText)
            ],200);
        }

        // Handle successful response
        $responseData = json_decode($response->getBody()->getContents(), true);
        $cardData = $responseData['card'];
        $limits = $cardData['limits'];
        $secureSettings = $cardData['3d_secure_settings'];

        //update Card record
        $card->card_id = $cardData['id']; //generating a unique card_id
        $card->account_id = $account_id;
        $card->type = $cardData['type'];
        $card->card_name = $cardData['name'];
        $card->masked_card_number = $cardData['masked_card_number'];
        $card->expiry_date = $cardData['expiry_date'];
        $card->status = $cardData['status'];
        $card->email = $secureSettings['email']?? null;
        $card->mobile = $secureSettings['mobile']?? null;

        $card->limits_daily_contactless_purchase = $limits['daily_contactless_purchase'];
        $card->limits_daily_internet_purchase = $limits['daily_internet_purchase'];
        $card->limits_daily_overall_purchase = $limits['daily_overall_purchase'];
        $card->limits_daily_purchase = $limits['daily_purchase'];
        $card->limits_daily_withdrawal = $limits['daily_withdrawal'];
        $card->limits_monthly_contactless_purchase = $limits['monthly_contactless_purchase'];
        $card->limits_monthly_internet_purchase = $limits['monthly_internet_purchase'];
        $card->limits_monthly_overall_purchase = $limits['monthly_overall_purchase'] ;
        $card->limits_monthly_purchase = $limits['monthly_purchase'];
        $card->limits_monthly_withdrawal = $limits['monthly_withdrawal'];

        $card->limits_daily_contactless_purchase_used = $limits['daily_contactless_purchase_used'];
        $card->limits_daily_internet_purchase_used = $limits['daily_internet_purchase_used'];
        // $card->limits_daily_overall_purchase_used = $limits['daily_overall_purchase_used'];
        $card->limits_daily_purchase_used = $limits['daily_purchase_used'];
        $card->limits_daily_withdrawal_used = $limits['daily_withdrawal_used'];
        $card->limits_monthly_contactless_purchase_used = $limits['monthly_contactless_purchase_used'];
        $card->limits_monthly_internet_purchase_used = $limits['monthly_internet_purchase_used'];
        // $card->limits_monthly_overall_purchase_used = $limits['monthly_overall_purchase_used'] ;
        $card->limits_monthly_purchase_used = $limits['monthly_purchase_used'];
        $card->limits_monthly_withdrawal_used = $limits['monthly_withdrawal_used'];

        $card->limits_daily_contactless_purchase_available = $limits['daily_contactless_purchase_available'];
        $card->limits_daily_internet_purchase_available = $limits['daily_internet_purchase_available'];
        // $card->limits_daily_overall_purchase_available = $limits['daily_overall_purchase_available'];
        $card->limits_daily_purchase_available = $limits['daily_purchase_available'];
        $card->limits_daily_withdrawal_available = $limits['daily_withdrawal_available'];
        $card->limits_monthly_contactless_purchase_available = $limits['monthly_contactless_purchase_available'];
        $card->limits_monthly_internet_purchase_available = $limits['monthly_internet_purchase_available'];
        // $card->limits_monthly_overall_purchase_available = $limits['monthly_overall_purchase_available'] ;
        $card->limits_monthly_purchase_available = $limits['monthly_purchase_available'];
        $card->limits_monthly_withdrawal_available = $limits['monthly_withdrawal_available'];

        $card->save();

        unset($card->id, $card->account_id, $card->card_number, $card->cvv2, $card->expiry_date, $card->card_password, $card->email,
        $card->limits_daily_overall_purchase,
        $card->limits_monthly_overall_purchase,
        $card->updated_at,
        $card->address1,
        $card->address2,
        $card->city,
        $card->company_name,
        $card->country_code,
        $card->dispatch_method,
        $card->first_name,
        $card->last_name,
        $card->phone,
        $card->postal_code,
        $card->contactless_enabled,
        $card->withdrawal_enabled,
        $card->internet_purchase_enabled,
        $card->all_time_limits_enabled,
        $card->receipts_reminder_enabled,
        $card->instant_spend_update_enabled
       );

        return response()->json([
            'success' =>true,
            'message' => 'Card Closed Successfully',
            // 'data' => $card
        ],200);
    }

    public function createVirtualCard(Request $request)
    {
        $accountData = Account::where('user_id', Auth::user()->id)->first();

         $validatedData = $request->validate([
            'account_id' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|string|min:8|regex:/^[A-Za-z0-9!"#;:?&*()+=\/\\,.{}\[\]]+$/',
            'card_type' => 'required',
            'mobileNo' => 'required'
        ], [
            'password.regex' => 'The password should be minimum 8 characters and must only contain A-Z a-z 0-9 ! " # ; : ? & * ( ) + = / \ , . [ ] { }',
            'mobile.required' => 'The 10-12 Digit Mobile No. should be prefixed with + sign following the international phone code.'
        ]
        );

        $requiredFields = [
            'limit_daily_contactless_purchase',
            'limit_monthly_contactless_purchase',
            'limit_daily_internet_purchase',
            'limit_monthly_internet_purchase',
            'limit_daily_purchase',
            'limit_monthly_purchase',
            'limit_daily_withdrawal',
            'limit_monthly_withdrawal',
        ];

        $errors = [];

        // Check required fields
        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                $errors[$field] = 'This field is required.';
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'There were validation errors.',
                'errors' => $errors,
            ], 422);
        }

        if (
            $request->limit_daily_contactless_purchase >= $request->limit_monthly_contactless_purchase ||
            $request->limit_daily_internet_purchase >= $request->limit_monthly_internet_purchase ||
            $request->limit_daily_purchase >= $request->limit_monthly_purchase ||
            $request->limit_daily_withdrawal >= $request->limit_monthly_withdrawal
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Daily limits must not exceed corresponding monthly limits.',
                'errors' => [
                    'limit_daily_contactless_purchase' => $request->limit_daily_contactless_purchase >= $request->limit_monthly_contactless_purchase ? 'Daily contactless purchase limit should be less than monthly limit.' : null,
                    'limit_daily_internet_purchase' => $request->limit_daily_internet_purchase >= $request->limit_monthly_internet_purchase ? 'Daily internet purchase limit should be less than monthly limit.' : null,
                    'limit_daily_purchase' => $request->limit_daily_purchase >= $request->limit_monthly_purchase ? 'Daily purchase limit should be less than monthly limit.' : null,
                    'limit_daily_withdrawal' => $request->limit_daily_withdrawal >= $request->limit_monthly_withdrawal ? 'Daily withdrawal limit should be less than monthly limit.' : null,
                ],
            ], 422);
        }

        if($request->account_id != $accountData->account_id){
            return response()->json([
                'success' =>false,
                'error' => 'use your own Account-ID',
            ],401);
        }

        $account_id = $request->account_id;
        $privateKey = $this->privateKey;

        $body = [
            '3d_secure_settings' => [
                'language_code' => 'ENG',
                'mobile' => $request->mobileNo??null,
                'password' => $validatedData['password'],
            ],
            'account_id' => $account_id,
            'name' => $validatedData['name'],
            'type' => $validatedData['card_type'],
            'limits' => [
                'daily_contactless_purchase' => (int)$request->limit_daily_contactless_purchase,
                'daily_internet_purchase' => (int)$request->limit_daily_internet_purchase,
                'daily_purchase' => (int)$request->limit_daily_purchase,
                'daily_withdrawal' => (int)$request->limit_daily_withdrawal,
                'monthly_contactless_purchase' => (int)$request->limit_monthly_contactless_purchase,
                'monthly_internet_purchase' => (int)$request->limit_monthly_internet_purchase,
                'monthly_purchase' => (int)$request->limit_monthly_purchase,
                'monthly_withdrawal' => (int)$request->limit_monthly_withdrawal,
                "transaction_contactless_purchase" =>(int)$request->limit_daily_contactless_purchase,
                "transaction_internet_purchase" => (int)$request->limit_daily_internet_purchase,
                "transaction_purchase" => (int)$request->limit_daily_purchase,
                "transaction_withdrawal" => (int)$request->limit_daily_withdrawal
            ]
        ];
        //added if Chip&Pin Type CARD
        if ($validatedData['card_type'] == 'ChipAndPin') {
            $body['delivery_address'] = [
                "address1" => $request->address1??'address 1',
                "address2" => $request->address2??'address line 2',
                "city" => $request->city??"Londonz",
                "company_name" => $request->company_name??"Company XYZ",
                "country_code" => $request->country_code??'GBR',
                "dispatch_method" => $request->dispatch_method??"DHLExpress",
                "first_name" => $request->first_name??"Aplha",
                "last_name" => $request->last_name??"Beta",
                "phone" => $request->phone2??'+449876543210',
                "postal_code" => $request->postal_code??'WV1 1AA'
            ];
            $body['security'] = [
                "contactless_enabled" => true,
                "withdrawal_enabled" => true,
                "internet_purchase_enabled" => true
            ];
            $body['embossing_name'] = $validatedData['name'];
            $body['personalization_product_code'] = "9999-0162";
            $body['card_fees'] = [
                [
                    'type' => 'AuthorizationATMBalanceInquiryFixedFee',
                    'percentage_part' => 1,
                    'min_amount' => 1
                ]
            ];
            $body['card_notification_settings'] = [
                "receipts_reminder_enabled" => true,
                "instant_spend_update_enabled" => true,
            ];
        }

        $payload = [
            'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
            'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
            'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
            'sub'=> "api-request",
            'rbh'=> base64_encode(hash('sha256',json_encode($body), true)),
            ];

        $token = FirebaseJWT::encode($payload, $privateKey, 'RS256');
        $url = 'https://api.wallester.eu/v1/cards';
        $client = new Client();

        try {
            
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Product-Code' => 'NEUROSYNCBDUSD',
                    'X-Audit-User-Id' => '542564e4-2084-4554-9318-b29d65adebea',
                    'X-Audit-Source-Type' => 'Backend',
                    'Content-Type' => 'application/json',
                ],
                'json' => $body,
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null;
            $errorDetails = $e->getMessage();
            

            if ($responseBody) {
                $decodedResponse = json_decode($responseBody, true);
                if (isset($decodedResponse['body']['3d_secure_settings.mobile'])) {
                    $errorDetails = $decodedResponse['body']['3d_secure_settings.mobile'];
                    return response()->json([
                        'success' => false,
                        'error' => $errorDetails
                    ],401);
                }
            }
 
            $decoded = json_decode($responseBody, true);

            $errorText = 'Unknown error';
            if (is_array($decoded) && isset($decoded['error_text'])) {
                $errorText = $decoded['error_text'];
            } else {
             Log::warning('Failed to decode response or error_text missing', [
                    'response_body' => $responseBody,
                    'decoded' => $decoded,
                ]);
            }
            Log::error($errorText);
            return response()->json([
                'success' => false,
                'error' => $errorText,
            ], 423);
        }

        // Handle successful response
        $responseData = json_decode($response->getBody()->getContents(), true);
        Log::info('Card Created : '.json_encode($responseData));
        $cardData = $responseData['card'];
        $limits = $cardData['limits'];
        $settingData= $cardData['3d_secure_settings'];

        //create new Card record
        $card = new Card();
        $card->card_id = $cardData['id']; //generating a unique card_id
        $card->account_id = $accountData->id;
        $card->type = $cardData['type'];
        $card->card_name = $cardData['name'];
        $card->masked_card_number = $cardData['masked_card_number'];
        $card->expiry_date = $cardData['expiry_date'];
        $card->status = $cardData['status'];
        $card->email = $settingData['email']??null;
        $card->card_password = $validatedData['password'];
        $card->mobile = $settingData['mobile']??null;

        if($cardData['type'] == 'ChipAndPin')
        {
            $delivery = $cardData['delivery_address'];
            $security = $cardData['security'];
            $notification = $cardData['card_notification_settings'];

            $card->address1 = $delivery['address1']??'address 1';
            $card->address2 = $delivery['address2']??'address line 2';
            $card->city = $delivery['city']??"Londonz";
            $card->company_name = $delivery['company_name']??"Company XYZ";
            $card->country_code = $delivery['country_code']??'GBR';
            $card->dispatch_method = $delivery['dispatch_method']??"DHLExpress";
            $card->first_name = $delivery['first_name']??"Aplha";
            $card->last_name = $delivery['last_name']??"Beta";
            $card->phone = $delivery['phone']??'9876543210';
            $card->postal_code = $delivery['postal_code']??'WV1 1AA';

            $card->contactless_enabled = $security['contactless_enabled'] ? 'true' : 'false';
            $card->withdrawal_enabled = $security['withdrawal_enabled'] ? 'true' : 'false';
            $card->internet_purchase_enabled = $security['internet_purchase_enabled'] ? 'true' : 'false';
            $card->all_time_limits_enabled = $security['all_time_limits_enabled'] ? 'true' : 'false';

            $card->receipts_reminder_enabled = $notification['receipts_reminder_enabled'] ? 'true' : 'false';
            $card->instant_spend_update_enabled = $notification['instant_spend_update_enabled'] ? 'true' : 'false';
        }

        $card->limits_daily_contactless_purchase = $limits['daily_contactless_purchase'];
        $card->limits_daily_internet_purchase = $limits['daily_internet_purchase'];
        $card->limits_daily_overall_purchase = $limits['daily_overall_purchase'];
        $card->limits_daily_purchase = $limits['daily_purchase'];
        $card->limits_daily_withdrawal = $limits['daily_withdrawal'];
        $card->limits_monthly_contactless_purchase = $limits['monthly_contactless_purchase'];
        $card->limits_monthly_internet_purchase = $limits['monthly_internet_purchase'];
        $card->limits_monthly_overall_purchase = $limits['monthly_overall_purchase'];
        $card->limits_monthly_purchase = $limits['monthly_purchase'];
        $card->limits_monthly_withdrawal = $limits['monthly_withdrawal'];

        $card->limits_daily_contactless_purchase_used = $limits['daily_contactless_purchase_used'];
        $card->limits_daily_internet_purchase_used = $limits['daily_internet_purchase_used'];
        // $card->limits_daily_overall_purchase_used = $limits['daily_overall_purchase_used'];
        $card->limits_daily_purchase_used = $limits['daily_purchase_used'];
        $card->limits_daily_withdrawal_used = $limits['daily_withdrawal_used'];
        $card->limits_monthly_contactless_purchase_used = $limits['monthly_contactless_purchase_used'];
        $card->limits_monthly_internet_purchase_used = $limits['monthly_internet_purchase_used'];
        // $card->limits_monthly_overall_purchase_used = $limits['monthly_overall_purchase_used'] ;
        $card->limits_monthly_purchase_used = $limits['monthly_purchase_used'];
        $card->limits_monthly_withdrawal_used = $limits['monthly_withdrawal_used'];

        $card->limits_daily_contactless_purchase_available = $limits['daily_contactless_purchase_available'];
        $card->limits_daily_internet_purchase_available = $limits['daily_internet_purchase_available'];
        // $card->limits_daily_overall_purchase_available = $limits['daily_overall_purchase_available'];
        $card->limits_daily_purchase_available = $limits['daily_purchase_available'];
        $card->limits_daily_withdrawal_available = $limits['daily_withdrawal_available'];
        $card->limits_monthly_contactless_purchase_available = $limits['monthly_contactless_purchase_available'];
        $card->limits_monthly_internet_purchase_available = $limits['monthly_internet_purchase_available'];
        // $card->limits_monthly_overall_purchase_available = $limits['monthly_overall_purchase_available'] ;
        $card->limits_monthly_purchase_available = $limits['monthly_purchase_available'];
        $card->limits_monthly_withdrawal_available = $limits['monthly_withdrawal_available'];

        $card->save();

        //start card decryption

        // Prepare the request body
        $requestBody2 = ['public_key' => 'LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUlJQklqQU5CZ2txaGtpRzl3MEJBUUVGQUFPQ0FROEFNSUlCQ2dLQ0FRRUEwTW5ZQWUzalZtakp6OWhKMWM4RwoxWklTTGtVdzZsMnVxcTZBcEovMTg3d1BwQklTRGFicVV0L0FGcTRyVldsT29ZUkYzbDJqb3Z6U3BxMjhXVk1zCkZ1MUV1WXRqbjhhRHNuVUx5RDRGUVJIbDFNd05QU0xNMlIyQzFLY1NYQjh4WDVST3EvSk5GR3N2MjhWTFlxU0wKcEppeFh4a1Z3Tit6K0xtMzZIOWhTUDlST096YXRGUEtzSjVMMDVaUVJ4Qlh5dmp5L1ZCa3ByTUpEekZzU2RoMwpjZHBacXJqK1lJY1N3aVRaUTBrMm5jLzUyWEZ0N0Vwa3Q2anBtUHZxSFRQUWJvdVd6dUwwZUNDQU41c1RIOFBBCkt4R3haMEptTnU5VThVNEw2bEdaYkNvZjJLZGZCL3NxUEJVMERoZHVVTzFrM1pOV1hsVGRrV001ajFtbG1BOXIKQVFJREFRQUIKLS0tLS1FTkQgUFVCTElDIEtFWS0tLS0tCg=='];
        try {
            $client2 = new Client();
            $payload2 = [
                'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
                'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
                'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
                'sub'=> "api-request",
                'rbh'=> base64_encode(hash('sha256',json_encode($requestBody2), true)),
                ];

            $token2 = FirebaseJWT::encode($payload2, $privateKey, 'RS256');
            $response2 = $client2->post('https://api.wallester.eu/v1/cards/'.$cardData['id'].'/encrypted-card-number', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token2,
                    'X-Product-Code' => 'NEUROSYNCBDUSD',
                    'X-Audit-User-Id' => '542564e4-2084-4554-9318-b29d65adebea',
                    'X-Audit-Source-Type' => 'Backend',
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody2,
            ]);

            $responseData2 = json_decode($response2->getBody()->getContents(), true);
            $message1 = $responseData2['encrypted_card_number'];

            $client3 = new Client();
            $payload3 =  [
                'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
                'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
                'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
                'sub'=> "api-request",
                'rbh'=> base64_encode(hash('sha256',json_encode($requestBody2), true)),
                ];
            $token3 = FirebaseJWT::encode($payload3, $privateKey, 'RS256');
            $response3 = $client3->post('https://api.wallester.eu/v1/cards/'.$cardData['id'].'/encrypted-cvv2', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token3,
                    'X-Product-Code' => 'NEUROSYNCBDUSD',
                    'X-Audit-User-Id' => '542564e4-2084-4554-9318-b29d65adebea',
                    'X-Audit-Source-Type' => 'Backend',
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody2,
            ]);

            $responseData3 = json_decode($response3->getBody()->getContents(), true);
            $message2 = $responseData3['encrypted_cvv2'];

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null;
            $errorDetails = $e->getMessage()??null;
            Log::error('error msg: '. $e->getMessage());
            return back()->with('error','Invalid Inputs Provided!');
        }

        // Handle successful response
        $client4 = new Client();
        $response4 = $client4->post('https://support.neurosyncventures.com/api/v1/decrypt-msg', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'encrypted_card_number' =>  $message1,
                'encrypted_cvv2' =>  $message2
            ],
        ]);

        $responseData4 = json_decode($response4->getBody()->getContents(), true);
        Log::info('decrypted data: ', ['response' => $responseData4]);
        $decryptCard = $responseData4['card_number'];
        $decryptCVV2 = $responseData4['cvv2'];

        $card->card_number = $decryptCard;
        $card->cvv2 = $decryptCVV2;
        $card->save();
        //end card decryption

        unset($card->id,  $card->email,
        $card->updated_at,
        $card->address1,
        $card->address2,
        $card->city,
        $card->company_name,
        $card->country_code,
        $card->dispatch_method,
        $card->first_name,
        $card->last_name,
        $card->phone,
        $card->postal_code,
        $card->contactless_enabled,
        $card->withdrawal_enabled,
        $card->internet_purchase_enabled,
        $card->all_time_limits_enabled,
        $card->receipts_reminder_enabled,
        $card->instant_spend_update_enabled
       );

        return response()->json([
            'success' => true,
            'message' => 'Card added successfully!',
            // 'data' => $card,
        ],200);
    }

    public function cardDetails($card_id)
    {
        $card = Card::where('card_id', $card_id)->first();
        unset($card->id,  $card->email,
        $card->updated_at,
        $card->address1,
        $card->address2,
        $card->city,
        $card->company_name,
        $card->country_code,
        $card->dispatch_method,
        $card->first_name,
        $card->last_name,
        $card->phone,
        $card->postal_code,
        $card->contactless_enabled,
        $card->withdrawal_enabled,
        $card->internet_purchase_enabled,
        $card->all_time_limits_enabled,
        $card->receipts_reminder_enabled,
        $card->instant_spend_update_enabled
       );

        return response()->json([
            'success' => true,
            'data' => $card,
        ],200);
    }

    public function updateCardLimits(Request $request, $card_id)
    {
        $requiredFields = [
            'limit_daily_contactless_purchase',
            'limit_monthly_contactless_purchase',
            'limit_daily_internet_purchase',
            'limit_monthly_internet_purchase',
            'limit_daily_purchase',
            'limit_monthly_purchase',
            'limit_daily_withdrawal',
            'limit_monthly_withdrawal',
        ];

        $errors = [];

        // Check required fields
        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                $errors[$field] = 'This field is required.';
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'There were validation errors.',
                'errors' => $errors,
            ], 422);
        }

        if (
            $request->limit_daily_contactless_purchase >= $request->limit_monthly_contactless_purchase ||
            $request->limit_daily_internet_purchase >= $request->limit_monthly_internet_purchase ||
            $request->limit_daily_purchase >= $request->limit_monthly_purchase ||
            $request->limit_daily_withdrawal >= $request->limit_monthly_withdrawal
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Daily limits must not exceed corresponding monthly limits.',
                'errors' => [
                    'limit_daily_contactless_purchase' => $request->limit_daily_contactless_purchase >= $request->limit_monthly_contactless_purchase ? 'Daily contactless purchase limit should be less than monthly limit.' : null,
                    'limit_daily_internet_purchase' => $request->limit_daily_internet_purchase >= $request->limit_monthly_internet_purchase ? 'Daily internet purchase limit should be less than monthly limit.' : null,
                    'limit_daily_purchase' => $request->limit_daily_purchase >= $request->limit_monthly_purchase ? 'Daily purchase limit should be less than monthly limit.' : null,
                    'limit_daily_withdrawal' => $request->limit_daily_withdrawal >= $request->limit_monthly_withdrawal ? 'Daily withdrawal limit should be less than monthly limit.' : null,
                ],
            ], 422);
        }

        $account_id = Account::where('user_id', Auth::user()->id)->first()->id;
        $card = Card::where('card_id', $card_id)->where('account_id',$account_id)->first();

        if($card == null){
            return response()->json([
                'success' => false,
                'error' => 'Card_id does not belong to your account.'
            ],401);
        }

        $body = [
            'limits' => [
                'daily_contactless_purchase' => (int)$request->limit_daily_contactless_purchase,
                'daily_internet_purchase' => (int)$request->limit_daily_internet_purchase,
                'daily_purchase' => (int)$request->limit_daily_purchase,
                'daily_withdrawal' => (int)$request->limit_daily_withdrawal,
                'monthly_contactless_purchase' => (int)$request->limit_monthly_contactless_purchase,
                'monthly_internet_purchase' => (int)$request->limit_monthly_internet_purchase,
                'monthly_purchase' => (int)$request->limit_monthly_purchase,
                'monthly_withdrawal' => (int)$request->limit_monthly_withdrawal,
                "transaction_contactless_purchase" =>(int)$request->limit_daily_contactless_purchase,
                "transaction_internet_purchase" => (int)$request->limit_daily_internet_purchase,
                "transaction_purchase" => (int)$request->limit_daily_purchase,
                "transaction_withdrawal" => (int)$request->limit_daily_withdrawal
            ]
        ];

        $payload = [
            'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
            'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
            'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
            'sub'=> "api-request",
            'rbh'=> base64_encode(hash('sha256',json_encode($body), true)),
            ];

        $privateKey = $this->privateKey;
        $token = FirebaseJWT::encode($payload, $privateKey, 'RS256');

        $client = new Client();

        try {
            $response = $client->patch('https://api.wallester.eu/v1/cards/' . $card_id . '/limits', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Product-Code' => 'NEUROSYNCBDUSD',
                    'X-Audit-User-Id' => '542564e4-2084-4554-9318-b29d65adebea',
                    'X-Audit-Source-Type' => 'Backend',
                    'Content-Type' => 'application/json',
                ],
                'json' => $body,
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorText = $errorResponse['error_text'] ?? 'An unknown error occurred';
            if($errorText == 'card is closed')
            {
                $card->status = 'Closed';
                $card->save();
            }
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ], $e->getResponse() ? $e->getResponse()->getStatusCode() : 500);
        }

         // Handle successful response
         $responseData = json_decode($response->getBody()->getContents(), true);
         $cardData = $responseData['card'];
         $limits = $cardData['limits'];
         $secureSettings = $cardData['3d_secure_settings'];

         //update Card record
         $card->card_id = $cardData['id']; //generating a unique card_id
         $card->account_id = $account_id;
         $card->type = $cardData['type'];
         $card->card_name = $cardData['name'];
         $card->masked_card_number = $cardData['masked_card_number'];
         $card->expiry_date = $cardData['expiry_date'];
         $card->status = $cardData['status'];
         $card->email = $secureSettings['email']?? null;
         $card->mobile = $secureSettings['mobile']?? null;

         $card->limits_daily_contactless_purchase = $limits['daily_contactless_purchase'];
         $card->limits_daily_internet_purchase = $limits['daily_internet_purchase'];
         $card->limits_daily_overall_purchase = $limits['daily_overall_purchase'];
         $card->limits_daily_purchase = $limits['daily_purchase'];
         $card->limits_daily_withdrawal = $limits['daily_withdrawal'];
         $card->limits_monthly_contactless_purchase = $limits['monthly_contactless_purchase'];
         $card->limits_monthly_internet_purchase = $limits['monthly_internet_purchase'];
         $card->limits_monthly_overall_purchase = $limits['monthly_overall_purchase'] ;
         $card->limits_monthly_purchase = $limits['monthly_purchase'];
         $card->limits_monthly_withdrawal = $limits['monthly_withdrawal'];

         $card->limits_daily_contactless_purchase_used = $limits['daily_contactless_purchase_used'];
         $card->limits_daily_internet_purchase_used = $limits['daily_internet_purchase_used'];
         // $card->limits_daily_overall_purchase_used = $limits['daily_overall_purchase_used'];
         $card->limits_daily_purchase_used = $limits['daily_purchase_used'];
         $card->limits_daily_withdrawal_used = $limits['daily_withdrawal_used'];
         $card->limits_monthly_contactless_purchase_used = $limits['monthly_contactless_purchase_used'];
         $card->limits_monthly_internet_purchase_used = $limits['monthly_internet_purchase_used'];
         // $card->limits_monthly_overall_purchase_used = $limits['monthly_overall_purchase_used'] ;
         $card->limits_monthly_purchase_used = $limits['monthly_purchase_used'];
         $card->limits_monthly_withdrawal_used = $limits['monthly_withdrawal_used'];

         $card->limits_daily_contactless_purchase_available = $limits['daily_contactless_purchase_available'];
         $card->limits_daily_internet_purchase_available = $limits['daily_internet_purchase_available'];
         // $card->limits_daily_overall_purchase_available = $limits['daily_overall_purchase_available'];
         $card->limits_daily_purchase_available = $limits['daily_purchase_available'];
         $card->limits_daily_withdrawal_available = $limits['daily_withdrawal_available'];
         $card->limits_monthly_contactless_purchase_available = $limits['monthly_contactless_purchase_available'];
         $card->limits_monthly_internet_purchase_available = $limits['monthly_internet_purchase_available'];
         // $card->limits_monthly_overall_purchase_available = $limits['monthly_overall_purchase_available'] ;
         $card->limits_monthly_purchase_available = $limits['monthly_purchase_available'];
         $card->limits_monthly_withdrawal_available = $limits['monthly_withdrawal_available'];

         $card->save();

         unset($card->id, $card->account_id, $card->card_number, $card->cvv2, $card->expiry_date, $card->card_password, $card->email,
         $card->limits_daily_overall_purchase,
         $card->limits_monthly_overall_purchase,
         $card->address1,
         $card->address2,
         $card->city,
         $card->company_name,
         $card->country_code,
         $card->dispatch_method,
         $card->first_name,
         $card->last_name,
         $card->phone,
         $card->postal_code,
         $card->contactless_enabled,
         $card->withdrawal_enabled,
         $card->internet_purchase_enabled,
         $card->all_time_limits_enabled,
         $card->receipts_reminder_enabled,
         $card->instant_spend_update_enabled
        );

        return response()->json([
            'success' =>true,
            'message' => 'Card Limits Updated',
            // 'data' => $card
        ],200);
    }

    public function updateCardName(Request $request, $card_id)
    {
        $validatedData = $request->validate([
            'card_name' => 'required|string|min:3',
        ]
        );
        $card = Card::where('card_id', $card_id)->first();
        if($card==null){
            return response()->json([
                'success' => false,
                'error' => 'Card not found'
            ],422);
        }
        $nickname= $request->card_name;
        $privateKey = $this->privateKey;

        $requestBody = [
            'name' => $nickname
        ];

        $payload = [
            'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
            'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
            'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
            'sub'=> "api-request",
            'rbh'=> base64_encode(hash('sha256',json_encode($requestBody), true)),
            ];
        // Use FirebaseJWT to encode the payload
        $token = FirebaseJWT::encode($payload, $privateKey, 'RS256');

        $url = 'https://api.wallester.eu/v1/cards/'. $card_id .'/name';

        $client = new Client();

        try {
            $response = $client->patch($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Product-Code' => 'NEUROSYNCBDUSD',
                    'X-Audit-User-Id' => '542564e4-2084-4554-9318-b29d65adebea',
                    'X-Audit-Source-Type' => 'Backend',
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ], $e->getResponse() ? $e->getResponse()->getStatusCode() : 500);
        }

        $responseData = json_decode($response->getBody()->getContents(), true);
        $cardData = $responseData['card'];
        $limits = $cardData['limits'];

        $card->type = $cardData['type'];
        $card->card_name = $cardData['name'];
        $card->expiry_date = $cardData['expiry_date'];
        $card->status = $cardData['status'];

        $card->limits_daily_contactless_purchase = $limits['daily_contactless_purchase'];
        $card->limits_daily_internet_purchase = $limits['daily_internet_purchase'];
        $card->limits_daily_overall_purchase = $limits['daily_overall_purchase'];
        $card->limits_daily_purchase = $limits['daily_purchase'];
        $card->limits_daily_withdrawal = $limits['daily_withdrawal'];
        $card->limits_monthly_contactless_purchase = $limits['monthly_contactless_purchase'];
        $card->limits_monthly_internet_purchase = $limits['monthly_internet_purchase'];
        $card->limits_monthly_overall_purchase = $limits['monthly_overall_purchase'] ;
        $card->limits_monthly_purchase = $limits['monthly_purchase'];
        $card->limits_monthly_withdrawal = $limits['monthly_withdrawal'];

        $card->limits_daily_contactless_purchase_used = $limits['daily_contactless_purchase_used'];
        $card->limits_daily_internet_purchase_used = $limits['daily_internet_purchase_used'];
        // $card->limits_daily_overall_purchase_used = $limits['daily_overall_purchase_used'];
        $card->limits_daily_purchase_used = $limits['daily_purchase_used'];
        $card->limits_daily_withdrawal_used = $limits['daily_withdrawal_used'];
        $card->limits_monthly_contactless_purchase_used = $limits['monthly_contactless_purchase_used'];
        $card->limits_monthly_internet_purchase_used = $limits['monthly_internet_purchase_used'];
        // $card->limits_monthly_overall_purchase_used = $limits['monthly_overall_purchase_used'] ;
        $card->limits_monthly_purchase_used = $limits['monthly_purchase_used'];
        $card->limits_monthly_withdrawal_used = $limits['monthly_withdrawal_used'];

        $card->limits_daily_contactless_purchase_available = $limits['daily_contactless_purchase_available'];
        $card->limits_daily_internet_purchase_available = $limits['daily_internet_purchase_available'];
        // $card->limits_daily_overall_purchase_available = $limits['daily_overall_purchase_available'];
        $card->limits_daily_purchase_available = $limits['daily_purchase_available'];
        $card->limits_daily_withdrawal_available = $limits['daily_withdrawal_available'];
        $card->limits_monthly_contactless_purchase_available = $limits['monthly_contactless_purchase_available'];
        $card->limits_monthly_internet_purchase_available = $limits['monthly_internet_purchase_available'];
        // $card->limits_monthly_overall_purchase_available = $limits['monthly_overall_purchase_available'] ;
        $card->limits_monthly_purchase_available = $limits['monthly_purchase_available'];
        $card->limits_monthly_withdrawal_available = $limits['monthly_withdrawal_available'];

        $card->save();

        unset($card->id, $card->account_id, $card->card_number, $card->cvv2, $card->expiry_date, $card->card_password, $card->email,
        $card->limits_daily_overall_purchase,
        $card->limits_monthly_overall_purchase,
        $card->address1,
        $card->address2,
        $card->city,
        $card->company_name,
        $card->country_code,
        $card->dispatch_method,
        $card->first_name,
        $card->last_name,
        $card->phone,
        $card->postal_code,
        $card->contactless_enabled,
        $card->withdrawal_enabled,
        $card->internet_purchase_enabled,
        $card->all_time_limits_enabled,
        $card->receipts_reminder_enabled,
        $card->instant_spend_update_enabled
       );

       return response()->json([
           'success' =>true,
           'message' => 'Name on Card Updated',
        //    'data' => $card
       ],200);
    }

    public function downloadCardStatement($card_id)
    {
        $payload = [
          'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
          'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
          'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
          'sub'=> "api-request",
          // 'rbh'=> base64_encode(hash('sha256',json_encode($body), true)),
          ];

        $privateKey = $this->privateKey;

        // Use FirebaseJWT to encode the payload
        $token = FirebaseJWT::encode($payload, $privateKey, 'RS256');

        $url = 'https://api.wallester.eu/v1/cards/' . $card_id . '/download-statement?statement_file_type=pdf&from_date=2024-01-01T00:00:01Z&to_date='. now()->toISOString() .'&timezone="UTC"&language_code=ENG';

        $client = new Client();

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Product-Code' => 'NEUROSYNCBDUSD',
                    'X-Audit-User-Id' => '542564e4-2084-4554-9318-b29d65adebea',
                    'X-Audit-Source-Type' => 'Backend',
                    'Content-Type' => 'application/json',
                ],
                // 'json' => $requestBody,
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle error response
            return response()->json([
                'success' => false,
                'error' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ], $e->getResponse() ? $e->getResponse()->getStatusCode() : 500);
        }

        // Handle successful response
        $responseData = json_decode($response->getBody()->getContents(), true);

        // Extract file information from the request
        $fileData = $responseData['file'];
        $fileName = $fileData['name'];
        $mimeType = $fileData['mime_type'];
        $body = $fileData['body'];

        // return response()->json([
        //     'fileName' => $fileName ,
        //     'mime_type'=>  $mimeType,
        //     'body'=> $body,

        // ],200);

        // Decode the base64 content
         $fileContent = base64_decode($body);
        // Create a response for the file download
        return Response::make($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function update3DS(Request $request, $card_id)
    {
        $card = Card::where('card_id', $card_id)->first();
        $account_id = Account::where('user_id', Auth::user()->id)->first()->id;

        $privateKey = $this->privateKey;

        $url = 'https://api.wallester.eu/v1/cards/' . $card_id . '/3d-secure';

        $client = new Client();

        // Prepare the request body
        $requestBody = [
            "mobile" => $request->mobileNo ?? '',
            "email" => $request->email ?? '',
            "password" => $request->password ?? '',
        ];
        $payload = [
            'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
            'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
            'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
            'sub'=> "api-request",
            'rbh'=> base64_encode(hash('sha256',json_encode($requestBody), true)),
            ];
        $token = FirebaseJWT::encode($payload, $privateKey, 'RS256');

        try {
            $response = $client->patch($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Product-Code' => 'NEUROSYNCBDUSD',
                    'X-Audit-User-Id' => '542564e4-2084-4554-9318-b29d65adebea',
                    'X-Audit-Source-Type' => 'Backend',
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);

            $errorText = $errorResponse['error_text'] ?? 'An unknown error occurred';

            if($errorText == 'card is closed')
            {
                $card->status = 'Closed';
                $card->save();
            }

            $errorText2 = $errorResponse['body']['mobile'] ?? 'An unknown error occurred';
            if( strtoupper($errorText2) == 'MOBILE MUST BE A VALID NUMBER, ONLY DIGITS WITH "+" PREFIX ALLOWED')
            {
                return response()->json([
                    'success' => false,
                    'error' => strtoupper($errorText2),
                ], $e->getResponse() ? $e->getResponse()->getStatusCode() : 500);
            }

            $errorText3 = $errorResponse['body']['password'] ?? 'An unknown error occurred';
            if($errorText3 == 'password contains unallowed characters')
            {
                return response()->json([
                    'success' => false,
                    'error' => strtoupper($errorText3),
                ], $e->getResponse() ? $e->getResponse()->getStatusCode() : 500);
            }

             // Handle error response
            return response()->json([
                'success' => false,
                'error' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ], $e->getResponse() ? $e->getResponse()->getStatusCode() : 500);
        }

        // Handle successful response
        $responseData = json_decode($response->getBody()->getContents(), true);
        $cardData = $responseData['card'];
        $limits = $cardData['limits'];
        $secureSettings = $cardData['3d_secure_settings'];

        //update Card record
        $card->card_id = $cardData['id']; //generating a unique card_id
        $card->account_id = $account_id;
        $card->type = $cardData['type'];
        $card->card_name = $cardData['name'];
        $card->masked_card_number = $cardData['masked_card_number'];
        $card->expiry_date = $cardData['expiry_date'];
        $card->status = $cardData['status'];
        $card->email = $secureSettings['email']?? null;
        $card->mobile = $secureSettings['mobile']?? null;
        $card->card_password = $request->password??null;

        $card->limits_daily_contactless_purchase = $limits['daily_contactless_purchase'];
        $card->limits_daily_internet_purchase = $limits['daily_internet_purchase'];
        $card->limits_daily_overall_purchase = $limits['daily_overall_purchase'];
        $card->limits_daily_purchase = $limits['daily_purchase'];
        $card->limits_daily_withdrawal = $limits['daily_withdrawal'];
        $card->limits_monthly_contactless_purchase = $limits['monthly_contactless_purchase'];
        $card->limits_monthly_internet_purchase = $limits['monthly_internet_purchase'];
        $card->limits_monthly_overall_purchase = $limits['monthly_overall_purchase'] ;
        $card->limits_monthly_purchase = $limits['monthly_purchase'];
        $card->limits_monthly_withdrawal = $limits['monthly_withdrawal'];

        $card->limits_daily_contactless_purchase_used = $limits['daily_contactless_purchase_used'];
        $card->limits_daily_internet_purchase_used = $limits['daily_internet_purchase_used'];
        // $card->limits_daily_overall_purchase_used = $limits['daily_overall_purchase_used'];
        $card->limits_daily_purchase_used = $limits['daily_purchase_used'];
        $card->limits_daily_withdrawal_used = $limits['daily_withdrawal_used'];
        $card->limits_monthly_contactless_purchase_used = $limits['monthly_contactless_purchase_used'];
        $card->limits_monthly_internet_purchase_used = $limits['monthly_internet_purchase_used'];
        // $card->limits_monthly_overall_purchase_used = $limits['monthly_overall_purchase_used'] ;
        $card->limits_monthly_purchase_used = $limits['monthly_purchase_used'];
        $card->limits_monthly_withdrawal_used = $limits['monthly_withdrawal_used'];

        $card->limits_daily_contactless_purchase_available = $limits['daily_contactless_purchase_available'];
        $card->limits_daily_internet_purchase_available = $limits['daily_internet_purchase_available'];
        // $card->limits_daily_overall_purchase_available = $limits['daily_overall_purchase_available'];
        $card->limits_daily_purchase_available = $limits['daily_purchase_available'];
        $card->limits_daily_withdrawal_available = $limits['daily_withdrawal_available'];
        $card->limits_monthly_contactless_purchase_available = $limits['monthly_contactless_purchase_available'];
        $card->limits_monthly_internet_purchase_available = $limits['monthly_internet_purchase_available'];
        // $card->limits_monthly_overall_purchase_available = $limits['monthly_overall_purchase_available'] ;
        $card->limits_monthly_purchase_available = $limits['monthly_purchase_available'];
        $card->limits_monthly_withdrawal_available = $limits['monthly_withdrawal_available'];

        $card->save();

        unset($card->id, $card->account_id, $card->card_number, $card->cvv2, $card->expiry_date, $card->email,
        $card->limits_daily_overall_purchase,
        $card->limits_monthly_overall_purchase,
        $card->address1,
        $card->address2,
        $card->city,
        $card->company_name,
        $card->country_code,
        $card->dispatch_method,
        $card->first_name,
        $card->last_name,
        $card->phone,
        $card->postal_code,
        $card->contactless_enabled,
        $card->withdrawal_enabled,
        $card->internet_purchase_enabled,
        $card->all_time_limits_enabled,
        $card->receipts_reminder_enabled,
        $card->instant_spend_update_enabled
       );

       return response()->json([
           'success' =>true,
           'message' => "Card's 3DS Updated",
        //    'data' => $card
       ],200);
    }
}
