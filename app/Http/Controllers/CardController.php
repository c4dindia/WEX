<?php

namespace App\Http\Controllers;

use App\Imports\CardsImport;
use App\Models\Account;
use App\Models\Card;
use App\Models\CardLimit;
use App\Models\CardStatement;
use App\Models\Company;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Firebase\JWT\JWT as FirebaseJWT;
use GuzzleHttp\Client;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CardController extends Controller
{
    public function showCard()
    {
        $cards =  Card::where('user_id', Auth::user()->id)->get();

        return view("client.cardlist", compact('cards'));
    }

    public function showSpecificCard($id)
    {
        $card = Card::join('companies', 'companies.user_id', '=', 'cards.user_id')
            ->select('cards.*', 'companies.company_name')
            ->where('cards.card_id', $id)->first();

        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "GET";
        $path = "/card/" . $card->card_id;
        $hmacString = $mstime . $methodType . $path;

        $body = json_encode((object) []);

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
                'verify' => false,
            ]);

            $result = json_decode($response->getBody(), true);

            CardLimit::updateOrCreate(
                [
                    'card_id' => $card->id,
                ],
                [
                    'dailyPurchase' => $result['limits']['dailyPurchase'],
                    'dailyWithdrawal' => $result['limits']['dailyWithdrawal'],
                    'dailyContactlessPurchase' => $result['limits']['dailyContactlessPurchase'],
                    'dailyInternetPurchase' => $result['limits']['dailyInternetPurchase'],
                    'dailyOverallPurchase' => $result['limits']['dailyOverallPurchase'],
                    'dailyPurchaseUsed' => $result['limits']['dailyPurchaseUsed'],
                    'dailyWithdrawalUsed' => $result['limits']['dailyWithdrawalUsed'],
                    'dailyContactlessPurchaseUsed' => $result['limits']['dailyContactlessPurchaseUsed'],
                    'dailyInternetPurchaseUsed' => $result['limits']['dailyInternetPurchaseUsed'],
                    'dailyOverallPurchaseUsed' => $result['limits']['dailyOverallPurchaseUsed'],
                    'dailyPurchaseAvailable' => $result['limits']['dailyPurchaseAvailable'],
                    'dailyWithdrawalAvailable' => $result['limits']['dailyWithdrawalAvailable'],
                    'dailyContactlessPurchaseAvailable' => $result['limits']['dailyContactlessPurchaseAvailable'],
                    'dailyInternetPurchaseAvailable' => $result['limits']['dailyInternetPurchaseAvailable'],
                    'dailyOverallPurchaseAvailable' => $result['limits']['dailyOverallPurchaseAvailable'],
                    'monthlyPurchase' => $result['limits']['monthlyPurchase'],
                    'monthlyWithdrawal' => $result['limits']['monthlyWithdrawal'],
                    'monthlyContactlessPurchase' => $result['limits']['monthlyContactlessPurchase'],
                    'monthlyInternetPurchase' => $result['limits']['monthlyInternetPurchase'],
                    'monthlyOverallPurchase' => $result['limits']['monthlyOverallPurchase'],
                    'monthlyPurchaseUsed' => $result['limits']['monthlyPurchaseUsed'],
                    'monthlyWithdrawalUsed' => $result['limits']['monthlyWithdrawalUsed'],
                    'monthlyContactlessPurchaseUsed' => $result['limits']['monthlyContactlessPurchaseUsed'],
                    'monthlyInternetPurchaseUsed' => $result['limits']['monthlyInternetPurchaseUsed'],
                    'monthlyOverallPurchaseUsed' => $result['limits']['monthlyOverallPurchaseUsed'],
                    'monthlyPurchaseAvailable' => $result['limits']['monthlyPurchaseAvailable'],
                    'monthlyWithdrawalAvailable' => $result['limits']['monthlyWithdrawalAvailable'],
                    'monthlyContactlessPurchaseAvailable' => $result['limits']['monthlyContactlessPurchaseAvailable'],
                    'monthlyInternetPurchaseAvailable' => $result['limits']['monthlyInternetPurchaseAvailable'],
                    'monthlyOverallPurchaseAvailable' => $result['limits']['monthlyOverallPurchaseAvailable'],
                ]
            );
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorBody = $e->getResponse()->getBody()->getContents();
            Log::error($e);
            return response()->json(json_decode($errorBody, true));
        }

        $limits = CardLimit::where('card_id', $card->id)->first();

        return view('client.carddetail', compact('card', 'limits'));
    }

    public function showSpecificCardPayments($id, $user_id = null)
    {
        if ($user_id) {
            $user_id = $user_id;
        } else {
            $user_id = Auth::user()->id;
        }

        $card = Card::where('card_id', $id)->first();

        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "POST";
        $path = "/card/statement";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'cardId' => $card->card_id,
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
            $cardTransactions = $result['transactions'];

            foreach ($cardTransactions as $transaction) {
                CardStatement::updateOrCreate(
                    [
                        'transaction_id' => $transaction['relatedCardTransactionId'],
                        'card_id' => $card->id,
                    ],
                    [
                        'user_id' => $user_id,
                        'card_id' => $card->id,
                        'transaction_id' => $transaction['relatedCardTransactionId'],
                        'amount' => $transaction['transactionAmount'],
                        'merchant_name' => $transaction['merchantName'],
                        'type' => $transaction['type'],
                        'date' => $transaction['createdAt'],
                        'transaction_status' => isset($transaction['declineDetails']) ? 'Failed' : 'Success',
                    ]
                );
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $error = json_decode($responseBody, true);

            Log::error($e);
            return back()->with('error', $error['message']);
        }

        $cardStatements = CardStatement::where('user_id', $user_id)
            ->where('card_id', $card->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.cardpayment', compact('cardStatements', 'card'));
    }

    public function timeRecordsCardTransaction(Request $request, $id)
    {
        $card = Card::where('card_id', $id)->first();

        $cardStatements = CardStatement::where('card_id', $card->id)
            ->where('date', '>=', $request->start_date)
            ->where('date', '<=', $request->end_date)
            ->orderBy('date', 'desc')
            ->get();

        return view('client.cardpayment', compact('cardStatements', 'card'));
    }

    public function saveCards(Request $request, $id = null)
    {
        if ($id) {
            $id = $id;
        } else {
            $id = Auth::user()->id;
        }

        $userId = Account::where('user_id', $id)->first()->striga_user_id;

        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "POST";
        $path = "/card/create";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'userId' => $userId,
            'nameOnCard' => $request->name,
            'type' => strtoupper($request->card_type),
            'threeDSecurePassword' => $request->password,
            'address' => [
                'addressLine1' => $request->address,
                'city' => $request->city,
                'postalCode' => $request->postal_code,
                'countryCode' => 'US',
            ],
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

            $card = Card::create([
                'user_id' => $id,
                'card_id' => $result['id'],
                'card_name' => $result['name'],
                'card_type' => $result['type'],
                'masked_card_number' => $result['maskedCardNumber'],
                'expiry_date' => $result['expiryData'],
                'card_status' => $result['status'],
                'linked_account_id' => $result['linkedAccountId'],
                'wallet_id' => $result['parentWalletId'],
                '3ds_password' => $request->password,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
            ]);

            if ($card) {
                CardLimit::create([
                    'card_id' => $card->id,
                    'dailyPurchase' => $result['limits']['dailyPurchase'],
                    'dailyWithdrawal' => $result['limits']['dailyWithdrawal'],
                    'dailyContactlessPurchase' => $result['limits']['dailyContactlessPurchase'],
                    'dailyInternetPurchase' => $result['limits']['dailyInternetPurchase'],
                    'dailyOverallPurchase' => $result['limits']['dailyOverallPurchase'],
                    'dailyPurchaseUsed' => $result['limits']['dailyPurchaseUsed'],
                    'dailyWithdrawalUsed' => $result['limits']['dailyWithdrawalUsed'],
                    'dailyContactlessPurchaseUsed' => $result['limits']['dailyContactlessPurchaseUsed'],
                    'dailyInternetPurchaseUsed' => $result['limits']['dailyInternetPurchaseUsed'],
                    'dailyOverallPurchaseUsed' => $result['limits']['dailyOverallPurchaseUsed'],
                    'dailyPurchaseAvailable' => $result['limits']['dailyPurchaseAvailable'],
                    'dailyWithdrawalAvailable' => $result['limits']['dailyWithdrawalAvailable'],
                    'dailyContactlessPurchaseAvailable' => $result['limits']['dailyContactlessPurchaseAvailable'],
                    'dailyInternetPurchaseAvailable' => $result['limits']['dailyInternetPurchaseAvailable'],
                    'dailyOverallPurchaseAvailable' => $result['limits']['dailyOverallPurchaseAvailable'],
                    'monthlyPurchase' => $result['limits']['monthlyPurchase'],
                    'monthlyWithdrawal' => $result['limits']['monthlyWithdrawal'],
                    'monthlyContactlessPurchase' => $result['limits']['monthlyContactlessPurchase'],
                    'monthlyInternetPurchase' => $result['limits']['monthlyInternetPurchase'],
                    'monthlyOverallPurchase' => $result['limits']['monthlyOverallPurchase'],
                    'monthlyPurchaseUsed' => $result['limits']['monthlyPurchaseUsed'],
                    'monthlyWithdrawalUsed' => $result['limits']['monthlyWithdrawalUsed'],
                    'monthlyContactlessPurchaseUsed' => $result['limits']['monthlyContactlessPurchaseUsed'],
                    'monthlyInternetPurchaseUsed' => $result['limits']['monthlyInternetPurchaseUsed'],
                    'monthlyOverallPurchaseUsed' => $result['limits']['monthlyOverallPurchaseUsed'],
                    'monthlyPurchaseAvailable' => $result['limits']['monthlyPurchaseAvailable'],
                    'monthlyWithdrawalAvailable' => $result['limits']['monthlyWithdrawalAvailable'],
                    'monthlyContactlessPurchaseAvailable' => $result['limits']['monthlyContactlessPurchaseAvailable'],
                    'monthlyInternetPurchaseAvailable' => $result['limits']['monthlyInternetPurchaseAvailable'],
                    'monthlyOverallPurchaseAvailable' => $result['limits']['monthlyOverallPurchaseAvailable'],
                ]);
            }

            return back()->withSuccess('Card added successfully!');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $error = json_decode($responseBody, true);

            Log::error($e);
            return back()->with('error', $error['message']);
        }
    }

    public function closeCard($id)
    {
        $card = Card::where('card_id', $id)->first();

        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "POST";
        $path = "/card/burn";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'cardId' => $card->card_id,
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
            $card->card_status = $result['status'];
            $card->save();

            return back()->withSuccess('Card closed successfully!');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $error = json_decode($responseBody, true);

            Log::error($e);
            return back()->with('error', $error['message']);
        }
    }

    public function freezeCard($id)
    {
        $card = Card::where('card_id', $id)->first();

        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "POST";
        $path = "/card/block";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'cardId' => $card->card_id,
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
            $card->card_status = $result['status'];
            $card->save();

            return back()->withSuccess('Card freezed successfully!');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $error = json_decode($responseBody, true);

            Log::error($e);
            return back()->with('error', $error['message']);
        }
    }

    public function unblockCard($id)
    {
        $card = Card::where('card_id', $id)->first();

        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "POST";
        $path = "/card/unblock";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'cardId' => $card->card_id,
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
            $card->card_status = $result['status'];
            $card->save();

            return back()->withSuccess('Card unfreezed successfully!');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $error = json_decode($responseBody, true);

            Log::error($e);
            return back()->with('error', $error['message']);
        }
    }

    public function update3dsSettings(Request $request, $id)
    {
        $card = Card::where('card_id', $id)->first();

        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "PATCH";
        $path = "/card/3ds";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'cardId' => $card->card_id,
            'threeDSecurePassword' => $request->password,
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
            Card::where('card_id', $card->card_id)->update(['3ds_password' => $request->password]);

            return back()->withSuccess("Card 3DS Settings Updated!");
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error($e);
            return back()->with('error', 'Error while updating 3DS Settings!');
        }
    }

    public function updateCardDailyLimits(Request $request, $id)
    {
        $card = Card::where('card_id', $id)->first();

        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "PATCH";
        $path = "/card/limits";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'cardId' => $card->card_id,
            'limits' => [
                'dailyWithdrawal' => (int)$request->limits_daily_withdrawal,
                'dailyPurchase' => (int)$request->limits_daily_purchase,
                'dailyInternetPurchase' => (int)$request->limits_daily_internet_purchase,
                'dailyContactlessPurchase' => (int)$request->limits_daily_contactless_purchase,
            ]
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
            CardLimit::where('id', $card->id)->update([
                'dailyContactlessPurchase' => $result['limits']['dailyContactlessPurchase'],
                'dailyInternetPurchase' => $result['limits']['dailyInternetPurchase'],
                'dailyPurchase' => $result['limits']['dailyPurchase'],
                'dailyWithdrawal' => $result['limits']['dailyWithdrawal'],
            ]);

            return back()->withSuccess("Daily Card's Limit Updated !");
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $error = json_decode($responseBody, true);

            Log::error($e);
            return back()->with('error', $error['message']);
        }
    }

    public function updateCardMonthlyLimits(Request $request, $id)
    {
        $card = Card::where('card_id', $id)->first();

        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "PATCH";
        $path = "/card/limits";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'cardId' => $card->card_id,
            'limits' => [
                'monthlyWithdrawal' => (int)$request->limits_monthly_withdrawal,
                'monthlyPurchase' => (int)$request->limits_monthly_purchase,
                'monthlyInternetPurchase' => (int)$request->limits_monthly_internet_purchase,
                'monthlyContactlessPurchase' => (int)$request->limits_monthly_contactless_purchase,
            ]
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
            CardLimit::where('id', $card->id)->update([
                'monthlyContactlessPurchase' => $result['limits']['monthlyContactlessPurchase'],
                'monthlyInternetPurchase' => $result['limits']['monthlyInternetPurchase'],
                'monthlyPurchase' => $result['limits']['monthlyPurchase'],
                'monthlyWithdrawal' => $result['limits']['monthlyWithdrawal'],
            ]);

            return back()->withSuccess("Monthly Card's Limit Updated !");
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $error = json_decode($responseBody, true);

            Log::error($e);
            return back()->with('error', $error['message']);
        }
    }
}
