<?php

namespace App\Http\Controllers;

use App\Imports\CardsImport;
use App\Models\Card;
use App\Models\CardLimit;
use App\Models\CardStatement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    public function showCard()
    {
        $cards =  Card::where('user_id', Auth::user()->id)->get();

        return view("client.cardlist", compact('cards'));
    }

    public function showSpecificCard($id)
    {
        $card = Card::find($id);

        return view('client.carddetail', compact('card'));
    }

    public function showSpecificCardPayments($id)
    {

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

    public function saveCards(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $url = 'https://services.encompass-suite.com/api/';
        $auth = base64_encode("Trinity Resource/webservices:Tr!nity1Res3@");
        $methodType = "POST";
        $path = "purchase-logs/v1";
        $invoice = rand(100000, 999999);

        $organization = $request->organization;
        if (preg_match('/^(.+?)\s*\((\d+)\)$/', $organization, $matches)) {
            $organizationName = trim($matches[1]);
            $companyId = $matches[2];
        }

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request($methodType, $url . $path, [
                'headers' => [
                    'Authorization' => 'Basic ' . $auth,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
                'json' => [
                    'amount' => $request->amount,
                    'user_defined_fields' => [
                        $request->firstName . ' ' . $request->lastName,
                        'TR-' . $invoice,
                        'Trinity Resource',
                    ],
                    'org_bank_id' => '0010',
                    'org_company_id' => (string)$companyId,
                    'cardholder_first_name' => $request->firstName . ' ' . $request->lastName,
                    'cardholder_address_street_1' => $user->address,
                    'cardholder_address_city' => $user->city,
                    'cardholder_address_state' => strtoupper(substr($user->state, 0, 2)),
                    'cardholder_address_postal_code' => $user->postal_code,
                    'cardholder_address_country' => $user->country,
                ],
                'verify' => false
            ]);

            $result = json_decode($response->getBody(), true);

            Card::create([
                'user_id' => $user->id,
                'card_id' => $result['id'],
                'cardholder_name' => $result['cardholder_first_name'],
                'card_number' => $result['virtual_card']['number'],
                'credit_limit' => $result['credit_limit'],
                'card_type' => 'Virtual',
                'expiry_date' => $result['virtual_card']['expiration'],
                'csc' => $result['virtual_card']['security_code'],
                'org_bank_id' => $result['org_bank_id'],
                'org_name' => $organizationName,
                'org_company_id' => $result['org_company_id'],
                'payment_status' => $result['payment_status']
            ]);

            return back()->withSuccess('Card added successfully!');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorMessage = $errorBody['Message'] ?? 'An error occurred while processing your request. Please try again.';

            return back()->with(['error' => $errorMessage]);
            Log::error($e);
        }
    }

    public function changeCardHolder(Request $request, $id)
    {
        $card = Card::where('id', $id)->first();
        $url = 'https://services.encompass-suite.com/api/';
        $auth = base64_encode("Trinity Resource/webservices:Tr!nity1Res3@");
        $methodType = "PUT";
        $path = "purchase-logs/v1/" . $card->card_id;

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request($methodType, $url . $path, [
                'headers' => [
                    'Authorization' => 'Basic ' . $auth,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
                'json' => [
                    'cardholder_first_name' => $request->firstName . ' ' . $request->lastName
                ],
                'verify' => false
            ]);
            $result = json_decode($response->getBody()->getContents(), true);
            $card->cardholder_name = $result['cardholder_first_name'];
            $card->save();

            return back()->withSuccess('Card Holder Name Updated!');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorMessage = $errorBody['Message'] ?? 'An error occurred while processing your request. Please try again.';

            return back()->with(['error' => $errorMessage]);
            Log::error($e);
        }
    }

    public function changeCardLimit(Request $request, $id)
    {
        $card = Card::where('id', $id)->first();
        $url = 'https://services.encompass-suite.com/api/';
        $auth = base64_encode("Trinity Resource/webservices:Tr!nity1Res3@");
        $methodType = "PUT";
        $path = "purchase-logs/v1/" . $card->card_id;

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request($methodType, $url . $path, [
                'headers' => [
                    'Authorization' => 'Basic ' . $auth,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
                'json' => [
                    'amount' => (int)$request->amount
                ],
                'verify' => false
            ]);
            $result = json_decode($response->getBody()->getContents(), true);
            $card->credit_limit = $result['credit_limit'];
            $card->save();

            return back()->withSuccess('Card Credit Limit Updated!');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            $errorMessage = $errorBody['Message'] ?? 'An error occurred while processing your request. Please try again.';

            return back()->with(['error' => $errorMessage]);
            Log::error($e);
        }
    }
}
