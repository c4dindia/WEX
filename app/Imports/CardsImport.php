<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Card;
use Maatwebsite\Excel\Concerns\ToCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;

class CardsImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $orgName;
    protected $orgId;

    public function __construct($orgName, $orgId)
    {
        $this->orgName = $orgName;
        $this->orgId = $orgId;
    }

    public function collection(Collection $rows)
    {
        $user = Auth::user();
        $url = 'https://services.encompass-suite.com/api/';
        $auth = base64_encode("Trinity Resource/webservices:Tr!nity1Res3@");
        $methodType = "POST";
        $path = "purchase-logs/v1";

        $invoice = rand(100000, 999999);

        foreach ($rows->skip(1) as $row) {
            $client = new \GuzzleHttp\Client();

            try {
                $response = $client->request($methodType, $url . $path, [
                    'headers' => [
                        'Authorization' => 'Basic ' . $auth,
                        'accept' => 'application/json',
                        'content-type' => 'application/json',
                    ],
                    'json' => [
                        'amount' => (int)$row[2],
                        'user_defined_fields' => [
                            $row[0] . ' ' . $row[1],
                            'TR-' . $invoice,
                            'Trinity Resource',
                        ],
                        'org_bank_id' => '0010',
                        'org_company_id' => (string)$this->orgId,
                        'cardholder_first_name' => $row[0] . ' ' . $row[1],
                        'cardholder_address_street_1' => $user->address,
                        'cardholder_address_city' => $user->city,
                        'cardholder_address_state' => strtoupper(substr($user->state, 0, 2)),
                        'cardholder_address_postal_code' => $user->postal_code,
                        'cardholder_address_country' => $user->country,
                    ],
                    'verify' => false
                ]);

                $result = json_decode($response->getBody()->getContents(), true);

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
                    'org_name' => $this->orgName,
                    'org_company_id' => $result['org_company_id'],
                    'payment_status' => $result['payment_status']
                ]);
            } catch (ClientException $e) {
                $errorBody = json_decode($e->getResponse()->getBody()->getContents(), true);
                $errorMessage = $errorBody['Message'];
                throw new \Exception($errorMessage ?? 'Card creation failed. Please try again.');
            }
        }
    }
}
