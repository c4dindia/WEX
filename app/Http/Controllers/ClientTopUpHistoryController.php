<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BankDetail;
use App\Models\Card;
use App\Models\Company;
use App\Models\Topup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientTopUpHistoryController extends Controller
{
    public function showClientsTopUpHistoryPage()
    {
        $user_id = Account::where('user_id', Auth::user()->id)->first()['striga_user_id'];
        $accountId = optional(Card::where('user_id', Auth::user()->id)->first())['linked_account_id'];

        if ($accountId) {
            $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
            $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
            $url = 'https://www.sandbox.striga.com/api/v1';

            $mstime = floor(microtime(true) * 1000);
            $methodType = "POST";
            $path = "/wallets/account/enrich";
            $hmacString = $mstime . $methodType . $path;

            $bodyArray = [
                'userId' => $user_id,
                'accountId' => $accountId
            ];
            $body = json_encode($bodyArray);

            $contentHash = md5($body);
            $hmacString .= $contentHash;
            $hmac = hash_hmac('sha256', $hmacString, $API_SECRET);
            $auth = 'HMAC ' . $mstime . ':' . $hmac;

            $client = new \GuzzleHttp\Client();
            try {
                $response = $client->request($methodType, $url . $path, [
                    'headers' => [
                        'Authorization' => $auth,
                        'accept' => 'application/json',
                        'api-key' => $API_KEY,
                        'content-type' => 'application/json',
                    ],
                    'json' => $bodyArray,
                    'verify' => false,
                ]);

                $result = json_decode($response->getBody(), true);

                BankDetail::updateOrCreate(
                    ['user_id' => Auth::user()->id],
                    [
                        'account_number' => $result['accountNumber'],
                        'account_holder_name' => $result['bankAccountHolderName'],
                        'internal_account_id' => $result['internalAccountId'],
                        'bank_name' => $result['bankName'],
                        'bank_address' => $result['bankAddress'],
                        'bic' => $result['bic'],
                        'iban' => $result['iban'],
                        'currency' => $result['currency'],
                        'bank_country' => $result['bankCountry'],
                        'account_status' => $result['status'],
                    ]
                );
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                Log::error($e);
            }
        }

        $topUps = Topup::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $companyName = Company::where('user_id', Auth::user()->id)->first()['company_name'];

        return view('client.topUpHistory', compact('topUps', 'companyName'));
    }

    public function clientTopUpRequest(Request $request)
    {
        $accountId  = Card::where('user_id', Auth::user()->id)->first()['linked_account_id'];

        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "PATCH";
        $path = "/simulate/accounts/deposit";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'accountId' => $accountId,
            'amount' => (string) ($request->topup_amount * 100)
        ];
        $body = json_encode($bodyArray);

        $contentHash = md5($body);
        $hmacString .= $contentHash;
        $hmac = hash_hmac('sha256', $hmacString, $API_SECRET);
        $auth = 'HMAC ' . $mstime . ':' . $hmac;

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request($methodType, $url . $path, [
                'headers' => [
                    'Authorization' => $auth,
                    'accept' => 'application/json',
                    'api-key' => $API_KEY,
                    'content-type' => 'application/json',
                ],
                'json' => $bodyArray,
                'verify' => false,
            ]);

            $result = json_decode($response->getBody(), true);

            Topup::create([
                'user_id' => Auth::user()->id,
                'account_id' => $accountId,
                'amount' => $request->topup_amount,
                'topup_status' => 'COMPLETED',
            ]);
            Account::where('user_id', Auth::user()->id)->update([
                'account_balance' => DB::raw('account_balance + ' . $request->topup_amount),
            ]);


            return back()->withSuccess('Topup added successfully!');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $error = json_decode($responseBody, true);

            Log::error($e);
            return back()->with('error', $error['message']);
        }
    }
}
