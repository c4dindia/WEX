<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountStatement;
use App\Models\Card;
use App\Models\CardStatement;
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
        $url = 'https://services.encompass-suite.com/api/';
        $auth = base64_encode("Trinity Resource/webservices:Tr!nity1Res3@");
        $methodType = "GET";
        $cards = Card::where('user_id', Auth::user()->id)->get();

        foreach ($cards as $card) {
            $path = "purchase-logs/v1/" . $card->card_id . '/transactions';
            $client = new \GuzzleHttp\Client();

            try {
                $response = $client->request($methodType, $url . $path, [
                    'headers' => [
                        'Authorization' => 'Basic ' . $auth,
                        'accept' => 'application/json',
                        'content-type' => 'application/json',
                    ],
                    'verify' => false
                ]);

                $result = json_decode($response->getBody()->getContents(), true);
                $transactions = $result['transactions'];

                foreach ($transactions as $transaction) {
                    CardStatement::updateOrCreate(
                        [
                            'card_id' => $card->id,
                            'transaction_id' => $transaction['id']
                        ],
                        [
                            'user_id' => Auth::user()->id,
                            'transaction_date' => $transaction['transaction_date'],
                            'posting_date' => $transaction['posting_date'],
                            'billing_amount' => $transaction['billing_amount'],
                            'transaction_type' => $transaction['transaction_type'],
                            'merchant_description' => $transaction['merchant_description'],
                            'is_credit' => $transaction['is_credit'],
                        ]
                    );
                }
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                Log::error($e);
            }
        }

        $transactions = CardStatement::join('cards', 'cards.id', '=', 'card_statements.card_id')->where('card_statements.user_id', Auth::user()->id)
            ->get()->sortBy('card_statements.transaction_date');

        return view('client.statements', compact('transactions'));
    }

    public function timeRecordsAccountStatement(Request $request)
    {
        $transactions = CardStatement::join('cards', 'cards.id', '=', 'card_statements.card_id')->where('card_statements.user_id', Auth::user()->id)
            ->where('card_statements.transaction_date', '>=', $request->start_date)
            ->where('card_statements.transaction_date', '<=', $request->end_date)
            ->get()->sortBy('card_statements.transaction_date');

        return view('client.statements', compact('transactions'));
    }

    public function userDownloadCardStatement()
    {
        $transaction = CardStatement::join('cards', 'cards.id', '=', 'card_statements.card_id')
            ->where('card_statements.user_id', Auth::user()->id)
            ->get();
        $userName = Auth::user()->first_name . ' ' . Auth::user()->last_name;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.cardtransaction', [
            'transactions' => $transaction,
            'start_date'   => "2025-01-01",
            'end_date'     => Carbon::today()->toDateString(),
            'userName'     => $userName
        ]);

        return $pdf->download('transactions_' . now()->format('Ymd_His') . '.pdf');
    }
}
