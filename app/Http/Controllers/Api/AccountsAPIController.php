<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ClientInviteCardUserMail;
use App\Models\Account;
use App\Models\Card;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Firebase\JWT\JWT as FirebaseJWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class AccountsAPIController extends Controller
{
    protected $privateKeyz = '-----BEGIN PRIVATE KEY-----
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
    public function accountDetails()
    {
        $accountData = Account::where('user_id',Auth::user()->id)
                ->select(
                    "user_id",
                    "account_id",
                    "reference_number",
                    "cards_count",
                    "balance",
                    "pending",
                    "available",
                    "total",
                    "status",
                    "currency_code",
                    "total_records_number",
                    "debit_turnover",
                    "credit_turnover",
                    "pending_amount",
                    "iban",
                    "swift_code",
                    "receiver_name",
                    "payment_details",
                    "bank_name",
                    "bank_address",
                    "registration_number",
                    "limit_daily_contactless_purchase",
                    "limit_daily_internet_purchase",
                    "limit_daily_purchase",
                    "limit_daily_withdrawal",
                    "limit_monthly_contactless_purchase",
                    "limit_monthly_internet_purchase",
                    "limit_monthly_purchase",
                    "limit_monthly_withdrawal",
                    "limit_daily_withdrawal_used",
                    "limit_daily_purchase_used",
                    "limit_daily_internet_purchase_used",
                    "limit_daily_contactless_purchase_used",
                    "limit_monthly_withdrawal_used",
                    "limit_monthly_purchase_used",
                    "limit_monthly_internet_purchase_used",
                    "limit_monthly_contactless_purchase_used",
                    "created_at",
                    "updated_at"
                )->first();
                $accountData->name = $accountData->user->name ?? null;
                unset($accountData->user);
                unset($accountData->user_id);
        return response()->json([
            'status' => true,
            'data' => $accountData
        ]);
    }

    public function refreshAccountData()
    {
        $id = Account::where('user_id',Auth::user()->id)->first()->account_id;
        $account = Account::where('account_id', $id)->first();

        $payload = [
            'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
            'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
            'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
            'sub'=> "api-request"
            ];

        $privateKey = $this->privateKeyz;

        // Use FirebaseJWT to encode the payload
        $token = FirebaseJWT::encode($payload, $privateKey, 'RS256');

        $url = 'https://api.wallester.eu/v1/accounts/' . $id ;

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
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle error response
            return response()->json([
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ], $e->getResponse() ? $e->getResponse()->getStatusCode() : 500);
        }

        // Handle successful response
        $responseData = json_decode($response->getBody()->getContents(), true);
        Log::info('Account Update: '. json_encode($responseData));
        $accountData = $responseData['account'];
        $limits = $accountData['limits'];

        //update Account record
        $account->user->name = $accountData['name'];
        $account->cards_count = $accountData['cards_count'];
        $account->reference_number = $accountData['reference_number']??null;
        $account->balance = $accountData['balance'];
        $account->available = $accountData['available_amount'];
        $account->pending = $accountData['blocked_amount'];
        $account->status = $accountData['status'];
        $account->currency_code = $accountData['currency_code'];

        $account->limit_daily_contactless_purchase = $limits['daily_contactless_purchase'];
        $account->limit_daily_internet_purchase = $limits['daily_internet_purchase'];
        $account->limit_daily_purchase = $limits['daily_purchase'];
        $account->limit_daily_withdrawal = $limits['daily_withdrawal'];
        $account->limit_monthly_contactless_purchase = $limits['monthly_contactless_purchase'];
        $account->limit_monthly_internet_purchase = $limits['monthly_internet_purchase'];
        $account->limit_monthly_purchase = $limits['monthly_purchase'];
        $account->limit_monthly_withdrawal = $limits['monthly_withdrawal'];

        $account->user->save();
        $account->save();

        $accountData = Account::where('account_id',$id)
                ->select(
                    "user_id",
                    "account_id",
                    "reference_number",
                    "cards_count",
                    "balance",
                    "pending",
                    "available",
                    "total",
                    "status",
                    "currency_code",
                    "total_records_number",
                    "debit_turnover",
                    "credit_turnover",
                    "pending_amount",
                    "iban",
                    "swift_code",
                    "receiver_name",
                    "payment_details",
                    "bank_name",
                    "bank_address",
                    "registration_number",
                    "limit_daily_contactless_purchase",
                    "limit_daily_internet_purchase",
                    "limit_daily_purchase",
                    "limit_daily_withdrawal",
                    "limit_monthly_contactless_purchase",
                    "limit_monthly_internet_purchase",
                    "limit_monthly_purchase",
                    "limit_monthly_withdrawal",
                    "limit_daily_withdrawal_used",
                    "limit_daily_purchase_used",
                    "limit_daily_internet_purchase_used",
                    "limit_daily_contactless_purchase_used",
                    "limit_monthly_withdrawal_used",
                    "limit_monthly_purchase_used",
                    "limit_monthly_internet_purchase_used",
                    "limit_monthly_contactless_purchase_used",
                    "created_at",
                    "updated_at"
                )->first();
        $accountData->name = $accountData->user->name ?? null;
        unset($accountData->user);
        unset($accountData->user_id);
        return response()->json([
            'status' => true,
            'data' => $accountData
        ]);
    }

    public function updateAccountLimits(Request $request)
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

        $id = Account::where('user_id',Auth::user()->id)->first()->account_id;
        $account = Account::where('account_id', $id)->first();

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
            ]
        ];

        $payload = [
            'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
            'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
            'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
            'sub'=> "api-request",
            'rbh'=> base64_encode(hash('sha256',json_encode($body), true)),
            ];

        $privateKey = $this->privateKeyz;
        $token = FirebaseJWT::encode($payload, $privateKey, 'RS256');

        $client = new Client();

        try {
            $response = $client->patch('https://api.wallester.eu/v1/accounts/' . $id . '/limits', [
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

            // return back()->with('error',"Daily Limit should be less than Monthly Limit!");

            // Handle error response
            return response()->json([
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ], $e->getResponse() ? $e->getResponse()->getStatusCode() : 500);
        }

        // Handle successful response
        $responseData = json_decode($response->getBody()->getContents(), true);
        Log::info('Account Limits Update: '. json_encode($responseData));
        $accountData = $responseData['account'];
        $limits = $accountData['limits'];

        //update Account record
        $account->user->name = $accountData['name'];
        $account->status = $accountData['status'];
        $account->currency_code = $accountData['currency_code'];

        $account->limit_daily_contactless_purchase = $limits['daily_contactless_purchase'];
        $account->limit_daily_internet_purchase = $limits['daily_internet_purchase'];
        $account->limit_daily_purchase = $limits['daily_purchase'];
        $account->limit_daily_withdrawal = $limits['daily_withdrawal'];
        $account->limit_monthly_contactless_purchase = $limits['monthly_contactless_purchase'];
        $account->limit_monthly_internet_purchase = $limits['monthly_internet_purchase'];
        $account->limit_monthly_purchase = $limits['monthly_purchase'];
        $account->limit_monthly_withdrawal = $limits['monthly_withdrawal'];

        $account->user->save();
        $account->save();

        $accountData = Account::where('account_id',$id)
                ->select(
                    "user_id",
                    "account_id",
                    "limit_daily_contactless_purchase",
                    "limit_daily_internet_purchase",
                    "limit_daily_purchase",
                    "limit_daily_withdrawal",
                    "limit_monthly_contactless_purchase",
                    "limit_monthly_internet_purchase",
                    "limit_monthly_purchase",
                    "limit_monthly_withdrawal",
                    "limit_daily_withdrawal_used",
                    "limit_daily_purchase_used",
                    "limit_daily_internet_purchase_used",
                    "limit_daily_contactless_purchase_used",
                    "limit_monthly_withdrawal_used",
                    "limit_monthly_purchase_used",
                    "limit_monthly_internet_purchase_used",
                    "limit_monthly_contactless_purchase_used",
                )->first();
        $accountData->name = $accountData->user->name ?? null;
        unset($accountData->user);
        unset($accountData->user_id);
        return response()->json([
            'status' => true,
            'message'=> "Account Limits Updated Succefully!",
            // 'data' => $accountData
        ]);
    }

    public function downloadAccountStatement()
    {
        $account = Account::where('user_id', Auth::user()->id)->first();

        $payload = [
            'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
            'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
            'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
            'sub'=> "api-request"
            ];

        $privateKey = $this->privateKeyz;

        // Use FirebaseJWT to encode the payload
        $token = FirebaseJWT::encode($payload, $privateKey, 'RS256');

        $url = 'https://api.wallester.eu/v1/accounts/' . $account->account_id . '/download-statement?statement_file_type=pdf&from_date=2024-01-01T00:00:01Z&to_date='. now()->toISOString() .'&timezone="UTC"&language_code=ENG';

        $client = new Client();

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'X-Product-Code' => 'NEUROSYNCBDUSD',
                    'X-Audit-User-Id' => '542564e4-2084-4554-9318-b29d65adebea',
                    'X-Audit-Source-Type' => 'Backend',
                    'Content-Type' => 'application/json',
                ]
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
             $respo = json_decode($e->getResponse()->getBody()->getContents(),true);
            // Handle error response
            return response()->json([
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ], $e->getResponse() ? $e->getResponse()->getStatusCode() : 500);
        }

        // Handle successful response
        $responseData = json_decode($response->getBody()->getContents(), true);
        Log::info('download : ', $responseData);

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

        // Create a response for the file download
        // Decode the base64 content
        $fileContent = base64_decode($body);
        return Response::make($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);

    }

    public function search(Request $request)
    {
        $query = $request->get('keyword');
        if($query == null){
            return response()->json([
                'success' => false,
                'message' => 'search keyword is null',
            ],401);
        }

        $account  = Account::where('user_id','=', Auth::user()->id)->first();
        $results = Card::where('account_id','=', $account->id )
            ->where(function ($q) use ($query) {
            $q->where('card_number', 'LIKE', "%{$query}%")
              ->orWhere('card_name', 'LIKE', "%{$query}%");
        })
        ->select('card_id', 'card_name', 'masked_card_number', 'type', 'status')
        ->orderBy('status', 'asc')
        ->get();

        return response()->json([
            'success' => true,
            'data'=> $results,
        ],200);
    }

    public function sendCardInvitation(Request $request)
    {
        $accId =  Account::where('user_id',Auth::user()->id)->first()->account_id; // Account ki "acc_id" column

        if(!$accId){
            return response()->json([
                'success' => false,
                'message' => 'Account not found',
            ],404);
        }

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'appname' => 'NeuroSync',
            'accId' => $accId,
        ];

        Mail::to($request->email)->send(new ClientInviteCardUserMail($data));
        return response()->json([
            "success" => true,
            "message" => "Invite sent via Mail successfully",
        ],200);
    }

}
