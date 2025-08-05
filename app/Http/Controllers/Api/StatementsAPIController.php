<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT as FirebaseJWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class StatementsAPIController extends Controller
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

    public function getAccountStatements()
    {
        $accountData  = Account::where('user_id','=', Auth::user()->id)->first();
        $available_amount = $accountData->available;

        $payload = [
            'iss'=> "542564e4-2084-4554-9318-b29d65adebea",
            'aud'=> "d3326f48-ba65-44c1-860e-6a7bdbc400d8",
            'exp'=> Carbon::now('UTC')->addSeconds(60)->timestamp,
            'sub'=> "api-request",
            // 'rbh'=> base64_encode(hash('sha256',$body, true)),
            ];

        $token = FirebaseJWT::encode($payload, $this->privateKey, 'RS256');

        $url = 'https://api.wallester.eu/v1/accounts/' . $accountData->account_id . '/statement?from_record='. $accountData->total_records_number .'&records_count=1000&include_transactions=true&include_account_adjustments=true&include_authorizations=true&include_fees=true&order_direction=asc';

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
                // 'body' => json_encode($body),
            ]);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorText = $errorResponse['error_text'] ?? 'An unknown error occurred';
            return $errorResponse;
            // return redirect()->back()->with('error', strtoupper($errorText));
        }

        // Handle successful response
        $responseData = json_decode($response->getBody()->getContents(), true);
        Log::info(' From API, Account Statements Updated: ', $responseData);
        $accountData->total_records_number = $responseData['total_records_number'] ??null;
        $accountData->debit_turnover = $responseData['debit_turnover']??null;
        $accountData->credit_turnover = $responseData['credit_turnover']?? null;
        $accountData->pending_amount = $responseData['pending_amount']?? null;

        if($responseData['records'] != null) {
            //add transactions
            foreach($responseData['records'] as $record)
            {
                $transaction = new Transaction();

                $transaction->transactions_id = $record['id'];
                $transaction->accounts_id = $accountData->id;
                $transaction->type = $record['type']??null;
                $transaction->card_id = $record['card_id']??null;
                $transaction->purchase_date =Carbon::parse( $record['purchase_date'])->format('Y-m-d H:i:s');
                $transaction->processed_at = $record['date'];
                $transaction->group = $record['group'];
                $transaction->description = $record['description']??null;
                $transaction->subtype = $record['sub_type']??null;
                $transaction->transaction_amount = $record['transaction_amount'];
                $transaction->transaction_currency_code = $record['transaction_currency_code'];
                $transaction->account_amount = $record['account_amount'];
                $transaction->account_currency_code = $record['account_currency_code'];
                $transaction->merchant_category_code = $record['merchant_category_code']??null;
                $transaction->merchant_id = $record['merchant_id']??null;
                $transaction->merchant_name = $record['merchant_name']??null;
                $transaction->merchant_city = $record['merchant_city']??null;
                $transaction->merchant_country_code = $record['merchant_country_code']??null;
                $transaction->icon_url = $record['enriched_merchant_data']['icon_url'] ??null ;
                $transaction->authorization_id = $record['original_authorization_id']??null;
                $transaction->status = $record['status'];
                $transaction->response = $record['response'];
                $transaction->response_code = $record['response_code']??null;
                $transaction->is_failed = ($record['is_declined'] == true) ? "true": "false";
                $transaction->has_payment_document_files = ($record['has_payment_document_files']== true) ? "true": "false";
                $transaction->has_payment_notes = ($record['has_payment_notes'] == true) ? "true": "false";
                $transaction->total_amount =$record['total_amount'];

                $transaction->save();
            }
        }
        $accountData->save();
        $accountStatements = Transaction::where('accounts_id',$accountData->id)->orderBy('id','desc')->get();
        unset($accountStatements->accounts_id);

        return response()->json([
            'success' => true ,
            'data' => [
                'statements' => $accountStatements,
                'credit_turnover' => $accountData->credit_turnover,
                'debit_turnover' => $accountData->debit_turnover,
                'pending' => $accountData->pending_amount
            ]
        ],200);
    }

    public function filteredStatements(Request $request)
    {
        $request->validate([
            'start_date'=> 'required|string|date_format:Y-m-d', // add date validation of format => 2025-05-02T08:05 date_format:Y-m-d\TH:i
            'end_date' => 'required|string|date_format:Y-m-d',
        ]);
        $accountData = Account::where('user_id',Auth::user()->id)->first();

        $accountStatements = Transaction::where('accounts_id',$accountData->id)
                                ->where('purchase_date', '>=', $request->start_date)
                                ->where('purchase_date', '<=', $request->end_date)
                                ->orderBy('purchase_date','desc')
                                ->get();
        unset($accountStatements->accounts_id);

        return response()->json([
            'success' =>true,
            'data' => $accountStatements ,
        ],200);
    }
}
