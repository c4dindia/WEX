<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Card;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ClientDashboardController extends Controller
{

    public function showClientRegister()
    {
        if (Auth::check()) {
            return redirect()->back()->with('error', 'You\'re already Logged In'); // Change this to your route name
        }

        return view('client.register');
    }

    public function storeClientRegistration(Request $request)
    {
        //email validation
        $request->validate(['email' => 'required|email|unique:users,email']);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country_code' => $request->countryCode,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'is_admin' => 2,
            'status' => 1,
        ]);

        return redirect()->route('showClientLogin')->with("success", "Your account has been created successfully. Please log in.");
    }

    public function showClientLogin()
    {
        if (Auth::check()) {
            if (auth()->user()->is_admin == '1') {
                return redirect()->route('showWallesterDashboard');
            } else {
                return redirect()->route('showClientDashboard')->with('error', 'You\'re already Logged In');
            }
        }

        return view('client.login');
    }

    public function showClientDashboard()
    {
        $cardsCount = Card::where('user_id', Auth::user()->id)->count();

        return view('client.dashboard', compact('cardsCount'));
    }

    public function showClientCreateACard()
    {
        return view('client.create-a-card');
    }

    public function searchBar(Request $request)
    {
        $query = $request->get('q');

        if (auth()->user()->is_admin == 3) {
            $results = Card::where('user_id', Auth::user()->id)
                ->where(function ($q) use ($query) {
                    $q->where('masked_card_number', 'LIKE', "%{$query}%")
                        ->orWhere('card_name', 'LIKE', "%{$query}%");
                })
                ->select('card_id', 'card_name', 'masked_card_number', 'card_type', 'card_status')
                ->orderBy('status', 'asc')
                ->get();
        } else if (auth()->user()->is_admin == 4) {
            $results = Card::where(function ($q) use ($query) {
                $q->where('masked_card_number', 'LIKE', "%{$query}%")
                    ->orWhere('card_name', 'LIKE', "%{$query}%");
            })
                ->select('card_id', 'card_name', 'masked_card_number', 'card_type', 'card_status')
                ->orderBy('status', 'asc')
                ->get();
        }

        return response()->json($results);
    }
}
