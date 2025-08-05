<?php

namespace App\Http\Controllers;

use App\Mail\UserRegisteredEmail;
use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegistrationApprovalController extends Controller
{
    public function showRegistrationApproval()
    {
        $requests = User::join('accounts', 'accounts.user_id', '=', 'users.id')
            ->join('companies', 'companies.user_id', '=', 'users.id')
            ->select('users.*', 'users.id as user_id', 'accounts.*', 'companies.company_name')
            ->where('users.status', 0)
            ->where('users.is_admin', 3)
            ->orderBy('users.created_at', 'desc')
            ->get();


        return view('admin.registration-approval', compact('requests'));
    }

    public function showRegistrationDetails($id)
    {
        $userData = User::join('accounts', 'accounts.user_id', '=', 'users.id')
            ->join('companies', 'companies.user_id', '=', 'users.id')
            ->select('users.*', 'users.id as user_id', 'accounts.*', 'companies.*')
            ->where('users.id', $id)
            ->first();

        return view('admin.registration-approval-details', compact('userData'));
    }

    public function saveRegistrationApproval($id)
    {
        $user = User::find($id);
        $user->status = "1";
        $user->save();

        $account = Account::where('user_id', $user->id)->first();

        $data = [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            'mobile' => [
                'countryCode' => $account->country_code,
                'number' => $account->phone,
            ],
            'dob' => [
                'year' => Carbon::parse($account->dob)->format('Y'),
                'month' => Carbon::parse($account->dob)->format('m'),
                'day' => Carbon::parse($account->dob)->format('d'),
            ],
            'address' => [
                'addressLine1' => $account->address,
                'city' => $account->city,
                'state' => $account->state,
                'country' => $account->country,
                'postalCode' => $account->postal_code,
            ]
        ];

        Mail::to($user->email)->send(new UserRegisteredEmail($data));

        if ($user) {
            $API_SECRET = '5cRi7VPyJTkTWuQceIDI5/SjOvF+gKaspwaCHUdSNaM=';
            $API_KEY = 'JW6Cw-OdwewEsa2Ze34ierM0xYVk3fSrD-8jKhf0LfQ=';
            $url = 'https://www.sandbox.striga.com/api/v1';

            $mstime = floor(microtime(true) * 1000);
            $methodType = "POST";
            $path = "/user/create";
            $hmacString = $mstime . $methodType . $path;

            $bodyArray = [
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName'],
                'email' => $data['email'],
                'mobile' => [
                    'countryCode' => $data['mobile']['countryCode'],
                    'number' => $data['mobile']['number'],
                ],
                'dateOfBirth' => [
                    'year' => $data['dob']['year'],
                    'month' => $data['dob']['month'],
                    'day' => $data['dob']['day'],
                ],
                'address' => [
                    'addressLine1' => $data['address']['addressLine1'],
                    'city' => $data['address']['city'],
                    'postalCode' => $data['address']['postalCode'],
                    'state' => $data['address']['state'],
                    'country' => $data['address']['country']
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

                Account::where('user_id', $user->id)->update([
                    'striga_user_id' => $result['userId'],
                ]);
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                Log::error($e);
            }

            return back()->withSuccess('Account added successfully!');
        }

        // return redirect('/registration-approval');
    }

    public function deleteRegistrationApproval($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
        };

        return back()->with('success', 'Request deleted successfully.');
    }
}
