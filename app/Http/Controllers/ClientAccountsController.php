<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ClientAccountsController extends Controller
{
    public function showClientsAccountsPage()
    {
        $account = User::find(Auth::user()->id);

        return view('client.accounts', compact('account'));
    }
}
