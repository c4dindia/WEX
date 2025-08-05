<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\UserRegisteredEmail;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Firebase\JWT\JWT as FirebaseJWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LoginAPiController extends Controller
{
    protected $company_id = "e156cf5c-41cd-4132-9c86-cedcc5e0e124";
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
    public function createToken(Request $request)
    {
        $request->validate([
                    'email' => 'required|email',
                    'password' => 'required',
                ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'incorrect credentials.'],403);
        }

        return response()->json([
            'success' => true,
            'token' => $user->createToken($request->email,['*'],Carbon::now()->addHours(1))->plainTextToken
        ],200) ;
    }

    public function logout(Request $request)
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ],200);
    }
    // 14|eAg8UUIETSJY0xAXwGVurUzi6OC9CDfPYLK2aZSYec1f577b

    public function registerAccount(Request $request)
    {
        //form validation
        $request->validate([
            'email' => 'required|email|unique:users,email',
            "name"  => 'required|string',
            "password" => 'required|string',
            "business_affiliate" => 'required|string',//
            "full_address" => 'required|max:256',
            "country" => "required",
            "postal_code" => 'required',
            "intended_card_use" => 'required|string',
            "volume_required" => 'required|integer',
            "date_of_incorporation" => 'required|date',
            "registration_number" => 'required|string',
            "vat_number" => 'required|string',
            "type_of_industry" => "required|string",
            "website_url" => 'required|url:http,https',
            "number_of_employees" => 'required|string',
            "iban" => 'required|string',
            "swift_code" => 'required|string',
            "receiver_name" => 'required|string',
            "payment_details" => 'required|string',
            "bank_name" => 'required|string',
            "bank_address" => 'required|string'
         ]);

        // Phone validation
        try {
            $request->validate([
                'phoneWithCode' => [
                    'required',
                    'regex:/^\+[1-9]\d{8,13}$/', // Ensure the phone number starts with a + and is followed by 8 to 12 digits
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error', 'Enter a valid mobile number. Ensure it starts with  "+" international code followed by 8 to 12 digits',
            ],500);
        }
        do {
            $number = Str::random(60);
        } while (User::where('api_token', ''.$number)->exists());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 3 ,
            'account_type' => 2,

            'business_affiliate' => $request->business_affiliate,
            'telephone' => $request->phoneWithCode,
            'full_address' => $request->full_address,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'intended_card_use' => $request->intended_card_use,
            'volume_required' => $request->volume_required,

            'date_of_incorporation' => $request->date_of_incorporation,
            'registration_number' => $request->registration_number,
            'vat_number' => $request->vat_number??null,
            'type_of_industry' => $request->type_of_industry,
            'website_url' => $request->website_url,
            'number_of_employees' => $request->number_of_employees,
            'language' =>  $request->language,

            'iban' => $request->iban,
            'swift_code' => $request->swift_code,
            'receiver_name' =>$request->receiver_name,
            'payment_details' => $request->payment_details,
            'bank_name' => $request->bank_name,
            'bank_address' => $request->bank_address,
            'api_token' => $number
        ]);

        // $user->status = "1"; //default is 0(user created but not allowed access), 1(user created and account created)
        $user->save();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'appname' => env('APP_NAME'),
        ];

        Mail::to($user->email)->send(new UserRegisteredEmail($data));

        if($user){
            return response()->json([
                'success' => true,
                'message'=> "Account added successfully! You will have login after admin's approval",
                // 'api_token' => $number,
            ],200);
        }

        return response()->json([
            'success' =>  false,
            'message' => 'Error occured',
        ],401);
    }
}
