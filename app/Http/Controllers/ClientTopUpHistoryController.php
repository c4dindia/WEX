<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ClientTopUpHistoryController extends Controller
{
    public function showClientsTopUpHistoryPage()
    {
        return view('client.topUpHistory');
    }
}
