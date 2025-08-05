<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KYCVerifyController extends Controller
{
    public function kyc_verify($id = null)
    {
        if ($id) {
            $user = User::join('accounts', 'accounts.user_id', '=', 'users.id')
                ->select('users.*', 'accounts.*')
                ->where('users.id', $id)
                ->first();
        } else {
            $user = User::join('accounts', 'accounts.user_id', '=', 'users.id')
                ->select('users.*', 'accounts.*')
                ->where('users.id', auth()->user()->id)
                ->first();
        }

        return view('client.kyc-verify')->with('user', $user);
    }

    public function verify_email()
    {
        $user_id = Account::where('user_id', auth()->user()->id)->first()['striga_user_id'];
        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "POST";
        $path = "/user/verify-email";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'userId' => $user_id,
            'verificationId' => '123456',
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
            Account::where('user_id', auth()->user()->id)->update(['email_verified' => 'true']);

            return response()->json($result);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorBody = $e->getResponse()->getBody()->getContents();
            Log::error($e);
            return response()->json(json_decode($errorBody, true));
        }
    }

    public function verify_mobile()
    {
        $user_id = Account::where('user_id', auth()->user()->id)->first()['striga_user_id'];
        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "POST";
        $path = "/user/verify-mobile";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'userId' => $user_id,
            'verificationCode' => '123456',
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
            Account::where('user_id', auth()->user()->id)->update(['mobile_verified' => 'true']);

            return response()->json($result);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorBody = $e->getResponse()->getBody()->getContents();
            Log::error($e);
            return response()->json(json_decode($errorBody, true));
        }
    }

    public function verify_kyc()
    {
        $user_id = Account::where('user_id', auth()->user()->id)->first()['striga_user_id'];
        $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
        $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
        $url = 'https://www.sandbox.striga.com/api/v1';

        $mstime = floor(microtime(true) * 1000);
        $methodType = "POST";
        $path = "/user/kyc/start";
        $hmacString = $mstime . $methodType . $path;

        $bodyArray = [
            'userId' => $user_id,
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
            Account::where('user_id', auth()->user()->id)->update(['kyc_verified' => 'true']);

            return response()->json($result);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorBody = $e->getResponse()->getBody()->getContents();
            Log::error($e);
            return response()->json(json_decode($errorBody, true));
        }
    }
}
