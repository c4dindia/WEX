<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountsType;
use App\Models\AssignRole;
use App\Models\Limitsetting;
use App\Models\Module;
use App\Models\Role;
use App\Models\TopUpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


//THIS IS ADIMN DASHBOARD
class DashboardController extends Controller
{
    public function showWallesterDashboard(){

        if(Auth::user()->is_admin == 3){
            return redirect()->route('showClientDashboard');
        }

        return view("wallester.dashboard");
    }

    public function showWallesterRoles(){
        $roles = Role::all();
        $modules = Module::where('status',1)->get();
        return view("admin.role",compact('roles','modules'));
    }

    public function showAccountsType(){

        $accounts = AccountsType::all();
        return view('admin.accountsType',compact('accounts'));
    }

    public function showAccounts()
    {
        $accountstypes = AccountsType::all();
        $accounts  = Account::all();
        //  dd($accounts);
        return view('admin.accounts',compact('accountstypes','accounts'));
    }

    public function showCompany(){
        return view('admin.company');
    }

    public function showTopUpApproval()
    {
        $topupRequests = TopUpRequest::orderBy('created_at','desc')->get();

        return view('admin.topup-approval',compact('topupRequests'));
    }

    public function showLimitsSettings()
    {
        $maxlimits = $maxlimits = Limitsetting::findOrFail(1);
        return view('admin.limits-settings',compact('maxlimits'));
    }

    public function updateAdminMonthlyAccountLimit(Request $request )
    {
        $maxlimits = Limitsetting::findOrFail(1);

        $maxlimits->max_account_monthly_contactless_purchase  =  $request->max_account_monthly_contactless_purchase;
        $maxlimits->max_account_monthly_internet_purchase  = $request->max_account_monthly_internet_purchase;
        $maxlimits->max_account_monthly_withdrawal  = $request->max_account_monthly_withdrawal;
        $maxlimits->max_account_monthly_purchase  = $request->max_account_monthly_purchase ;

        $maxlimits->save();

        return redirect()->back()->with('success','max account limits updated');
    }

    public function updateAdminMonthlyCardLimit(Request $request )
    {
        $maxlimits = Limitsetting::findOrFail(1);

        $maxlimits->max_card_monthly_contactless_purchase  =  $request->max_card_monthly_contactless_purchase;
        $maxlimits->max_card_monthly_internet_purchase  = $request->max_card_monthly_internet_purchase;
        $maxlimits->max_card_monthly_withdrawal  = $request->max_card_monthly_withdrawal;
        $maxlimits->max_card_monthly_purchase  = $request->max_card_monthly_purchase ;

        $maxlimits->save();

        return redirect()->back()->with('success','max card limits updated');
    }
}
