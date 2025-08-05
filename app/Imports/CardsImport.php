<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\Card;
use App\Models\Limitsetting;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class CardsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $accountId;

    public function __construct($accountId)
    {
        $this->accountId = $accountId;
    }

    public function model(array $row)
    {
        Validator::make($row, [
            'card_name' => 'required|string',
            'password' => 'required|string',
            'type' => 'required|string',
            // 'email' => 'email|nullable',
            // 'mobile' => 'string|nullable|regex:/^\+\d+$/',
        ])->validate();

        $this->saveCard([
            'card_name' => $row['card_name'],
            'password' => $row['password'],
            'type' => $row['type'],
            'email' => $row['email'],
            'mobile' => "+" . $row['mobile'],
            'limits_daily_contactless_purchase'=>$row['limits_daily_contactless_purchase'],
            'limits_daily_internet_purchase'=> $row['limits_daily_internet_purchase'],
            'limits_daily_purchase'=>$row['limits_daily_purchase'],
            'limits_daily_withdrawal'=> $row['limits_daily_withdrawal'],
            'limits_monthly_contactless_purchase'=>$row['limits_monthly_contactless_purchase'],
            'limits_monthly_internet_purchase'=>$row['limits_monthly_internet_purchase'],
            'limits_monthly_purchase'=>$row['limits_monthly_purchase'],
            'limits_monthly_withdrawal'=>$row['limits_monthly_withdrawal'],
        ]);
    }

    private function saveCard($validatedData)
    {
        $apiKey = 'ttaXGI1pSapk8HE5Bod7QIttYAr2MDw60nL6prfnlRXBdGMQf2Bwi9XfEINbRKwHXZtItIBDuEknXB3gVgOt7YniFeu7VPnUKmHT';
        $timestamp = now()->timestamp;
        $account_id = Account::where('id', $this->accountId)->first()->account_id;

        $payload = [
            'api_key' => $apiKey,
            'ts' => $timestamp
        ];

        $privateKey = '-----BEGIN PRIVATE KEY-----
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

        //generate the JWT token
        $token = JWT::encode($payload, $privateKey, 'RS256');

        $url = 'https://api-frontend.wallester.com/v1/cards';

        $client = new Client();
        $maxlimits = Limitsetting::find(1);

        $requestBody = [
            '3d_secure_settings' => [
                'email' => $validatedData['email']??'',
                'language_code' => 'ENG',
                'mobile' => $validatedData['mobile']??'',
                'password' => $validatedData['password'],
                'type' => 'SMSOTPAndStaticPassword',
            ],
            'account_id' => $account_id,
            'name' => $validatedData['card_name'],
            'type' => $validatedData['type'],
            'limits'=> [
            'daily_contactless_purchase'=>((int)$validatedData['limits_daily_contactless_purchase'] < (int)$maxlimits->max_card_monthly_contactless_purchase ) ? (int)$validatedData['limits_daily_contactless_purchase'] : $maxlimits->max_card_monthly_contactless_purchase,
            'daily_internet_purchase'=> ((int)$validatedData['limits_daily_internet_purchase'] < (int)$maxlimits->max_card_monthly_internet_purchase) ? (int)$validatedData['limits_daily_internet_purchase'] : $maxlimits->max_card_monthly_internet_purchase,
            // 'daily_overall_purchase'=> 9999999.99,
            'daily_purchase'=> ((int)$validatedData['limits_daily_purchase'] < (int)$maxlimits->max_card_monthly_purchase ) ? (int)$validatedData['limits_daily_purchase'] : $maxlimits->max_card_monthly_purchase,
            'daily_withdrawal'=> ((int)$validatedData['limits_daily_withdrawal'] < (int)$maxlimits->max_card_monthly_withdrawal)  ?(int)$validatedData['limits_daily_withdrawal'] : $maxlimits->max_card_monthly_withdrawal,
            'monthly_contactless_purchase'=> ((int)$maxlimits->max_card_monthly_contactless_purchase >= (int)$validatedData['limits_monthly_contactless_purchase']) ? (int)$validatedData['limits_monthly_contactless_purchase'] : $maxlimits->max_card_monthly_contactless_purchase,
            'monthly_internet_purchase'=> ((int)$maxlimits->max_card_monthly_internet_purchase >= (int)$validatedData['limits_monthly_internet_purchase'] ) ? (int)$validatedData['limits_monthly_internet_purchase'] : $maxlimits->max_card_monthly_internet_purchase,
            // 'monthly_overall_purchase'=> 9999999.99,
            'monthly_purchase'=> ((int)$maxlimits->max_card_monthly_purchase >= (int)$validatedData['limits_monthly_purchase']) ? (int)$validatedData['limits_monthly_purchase'] : $maxlimits->max_card_monthly_purchase,
            'monthly_withdrawal'=> ((int)$maxlimits->max_card_monthly_withdrawal >= (int)$validatedData['limits_monthly_withdrawal']) ? (int)$validatedData['limits_monthly_withdrawal'] : $maxlimits->max_account_monthly_withdrawal,
            'transaction_contactless_purchase' => ((int)$validatedData['limits_daily_contactless_purchase'] < (int)$maxlimits->max_card_monthly_contactless_purchase ) ? (int)$validatedData['limits_daily_contactless_purchase'] : $maxlimits->max_card_monthly_contactless_purchase,
            'transaction_internet_purchase' => ((int)$validatedData['limits_daily_internet_purchase'] < (int)$maxlimits->max_card_monthly_internet_purchase) ? (int)$validatedData['limits_daily_internet_purchase'] : $maxlimits->max_card_monthly_internet_purchase,
            'transaction_purchase' =>  ((int)$validatedData['limits_daily_purchase'] < (int)$maxlimits->max_card_monthly_purchase ) ? (int)$validatedData['limits_daily_purchase'] : $maxlimits->max_card_monthly_purchase,
            'transaction_withdrawal' => ((int)$validatedData['limits_daily_withdrawal'] < (int)$maxlimits->max_card_monthly_withdrawal) ?(int)$validatedData['limits_daily_withdrawal'] : $maxlimits->max_card_monthly_withdrawal,
            ],
        ];


        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            $cardData = $responseData['card'];
            $limits = $cardData['limits'];
            $settingData= $cardData['3d_secure_settings'];

            //save the card to database
                $card = new Card();
                $card->card_id = $cardData['id']; //generating a unique card_id
                $card->account_id = $this->accountId;
                $card->type = $cardData['type'];
                $card->card_name = $cardData['name'];
                $card->masked_card_number = $cardData['masked_card_number'];
                $card->expiry_date = $cardData['expiry_date'];
                $card->status = $cardData['status'];
                $card->email = $settingData['email']??null;
                $card->card_password = $validatedData['password'];
                $card->mobile = $settingData['mobile']??null;
                $card->card_role_type = 'expense';

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

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle exception
            throw new \Exception($e->getMessage());
        }
    }
}
