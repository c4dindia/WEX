<?php

namespace App\Http\Controllers;

use App\Mail\ClientInviteCardUserMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ClientCreateACardController extends Controller
{
    public function clientInviteCardUser(Request $request)
    {
        $data = [
            'name' => $request->cardholderName,
            'email' => $request->email,
            'appname' => 'C4D',
        ];

        Mail::to($request->email)->send(new ClientInviteCardUserMail($data));
        return redirect()->back()->with("success","Invite E-mail Sent!");
    }

}
