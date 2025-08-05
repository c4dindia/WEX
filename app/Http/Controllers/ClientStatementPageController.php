<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountStatement;
use App\Models\Card;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Firebase\JWT\JWT as FirebaseJWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientStatementPageController extends Controller
{
    public function showClientStatements()
    {
        $accountId = optional(Card::where('user_id', Auth::user()->id)->first())->linked_account_id;

        if ($accountId) {
            $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
            $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
            $url = 'https://www.sandbox.striga.com/api/v1';

            $mstime = floor(microtime(true) * 1000);
            $methodType = "POST";
            $path = "/wallets/get/account/statement";
            $hmacString = $mstime . $methodType . $path;

            $bodyArray = [
                'userId' => Account::where('user_id', Auth::user()->id)->first()['striga_user_id'],
                'accountId' => $accountId,
                'startDate' => Carbon::parse('2024-01-01')->valueOf(),
                'endDate' => Carbon::now()->valueOf(),
                'page' => 1,
                'limit' => 100,
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
                $transactions = $result['transactions'];

                foreach ($transactions as $transaction) {
                    AccountStatement::updateOrCreate(
                        [
                            'account_id' => $transaction['accountId'],
                            'transaction_id' => $transaction['id'],
                        ],
                        [
                            'user_id' => Auth::user()->id,
                            'account_id' => $transaction['accountId'],
                            'source_owner_id' => $transaction['sourceSyncedOwnerId'],
                            'source_destination_id' => $transaction['destinationSyncedOwnerId'] ?? null,
                            'transaction_id' => $transaction['id'],
                            'credit' => isset($transaction['credit']) ? $transaction['credit'] / 100 : null,
                            'debit' => isset($transaction['debit']) ? $transaction['debit'] / 100 : null,
                            'type' => $transaction['txType'],
                            'description' => $transaction['memo'],
                            'date' => $transaction['timestamp']
                        ]
                    );
                }
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $error = json_decode($responseBody, true);

                Log::error($e);
                return back()->with('error', $error['message']);
            }
        }

        $transactions = AccountStatement::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                if (!is_null($transaction->credit)) {
                    $userIdToLookup = $transaction->source_owner_id;
                } else if (!is_null($transaction->debit)) {
                    $userIdToLookup = $transaction->source_destination_id ?? $transaction->source_owner_id;
                }

                $source_destination = Account::join('users', 'users.id', '=', 'accounts.user_id')
                    ->join('companies', 'companies.user_id', '=', 'users.id')
                    ->select('companies.company_name')
                    ->where('accounts.striga_user_id', $userIdToLookup)
                    ->first();

                $transaction->source_destination = $source_destination->company_name;
                return $transaction;
            });

        $totalCredit = $transactions->sum(function ($tx) {
            return $tx->credit ?? 0;
        });

        $totalDebit = $transactions->sum(function ($tx) {
            return $tx->debit ?? 0;
        });

        return view('client.statements', compact('transactions', 'totalCredit', 'totalDebit'));
    }

    public function timeRecordsAccountStatement(Request $request)
    {
        $transactions = AccountStatement::where('user_id', Auth::user()->id)
            ->where('date', '>=', $request->start_date)
            ->where('date', '<=', $request->end_date)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($transaction) {
                if (!is_null($transaction->credit)) {
                    $userIdToLookup = $transaction->source_owner_id;
                } else if (!is_null($transaction->debit)) {
                    $userIdToLookup = $transaction->source_destination_id ?? $transaction->source_owner_id;
                }

                $source_destination = Account::join('users', 'users.id', '=', 'accounts.user_id')
                    ->join('companies', 'companies.user_id', '=', 'users.id')
                    ->select('companies.company_name')
                    ->where('accounts.striga_user_id', $userIdToLookup)
                    ->first();

                $transaction->source_destination = $source_destination->company_name;
                return $transaction;
            });

        $totalCredit = $transactions->sum(function ($tx) {
            return $tx->credit ?? 0;
        });

        $totalDebit = $transactions->sum(function ($tx) {
            return $tx->debit ?? 0;
        });

        return view('client.statements', compact('transactions', 'totalCredit', 'totalDebit'));
    }
}
