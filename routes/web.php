<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AffiliatePaymentCardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ClientAccountsController;
use App\Http\Controllers\ClientCardRequestsController;
use App\Http\Controllers\ClientCreateACardController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientStatementPageController;
use App\Http\Controllers\ClientTopUpHistoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KYCVerifyController;
use App\Http\Controllers\PaybisWebhookController;
use App\Http\Controllers\PayRollCardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationApprovalController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WallesterNotificationEventController;
use App\Models\AccountsType;
use App\Models\Card;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('front-page');
})->name('showFrontPage');


Route::get('/dashboard', function () {
    return redirect('/wallester-dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //admin dashboard
    Route::get('/wallester-dashboard', [DashboardController::class, 'showWallesterDashboard'])->name('showWallesterDashboard');
    Route::get('/wallester-roles', [DashboardController::class, 'showWallesterRoles'])->name('showWallesterRoles');

    //roles
    Route::post('/add-role', [RoleController::class, 'add_role'])->name('add_role');
    Route::get('/edit/role/{id}', [RoleController::class, 'edit_role']);
    Route::post('/update_role', [RoleController::class, 'update_role'])->name('update_role');
    Route::get('/delete/role/{id}', [RoleController::class, 'delete_role']);
    Route::get('/check_assigned_module', [RoleController::class, 'check_assigned_module'])->name('check_assigned_module');
    Route::post('/save_assign_module', [RoleController::class, 'save_assign_module'])->name('save_assign_module');

    //users
    Route::get('/user', [UserController::class, 'user'])->name('user');
    Route::post('/add-user', [UserController::class, 'add_user'])->name('add_user');
    Route::get('/edit/user/{id}', [UserController::class, 'edit_user']);
    Route::post('/update_user', [UserController::class, 'update_user'])->name('update_user');
    Route::get('/delete/user/{id}', [UserController::class, 'delete_user']);
    Route::get('/check_assigned_role', [UserController::class, 'check_assigned_role'])->name('check_assigned_role');
    Route::post('/save_assign_role', [UserController::class, 'save_assign_role'])->name('save_assign_role');

    //Admin registration approval
    Route::get('/registration-approval', [RegistrationApprovalController::class, 'showRegistrationApproval'])->name('showRegistrationApproval');
    Route::get('/registration/details/{id}', [RegistrationApprovalController::class, 'showRegistrationDetails'])->name('showRegistrationDetails');
    Route::get('/registration-approval/save/{id}', [RegistrationApprovalController::class, 'saveRegistrationApproval'])->name('saveRegistrationApproval');
    Route::get('/delete-registration-request/{id}', [RegistrationApprovalController::class, 'deleteRegistrationApproval'])->name('deleteRegistrationApproval');
});

Route::group(['middleware' => ['auth', 'check.client.status']], function () {

    Route::get('/client-dashboard', [ClientDashboardController::class, 'showClientDashboard'])->name('showClientDashboard');
    Route::get('/client-accounts', [ClientAccountsController::class, 'showClientsAccountsPage'])->name('showClientsAccountsPage');
    Route::get('/client-create-a-card', [ClientDashboardController::class, 'showClientCreateACard'])->name('showClientCreateACard');
    Route::get('/search', [ClientDashboardController::class, 'searchBar'])->name('searchBar');
    //Expense cards
    Route::get('/cards', [CardController::class, 'showCard'])->name('showCard');
    Route::get('/card/{id}', [CardController::class, 'showSpecificCard'])->name('showSpecificCard'); //for specific card detail
    Route::get('/card/{id}/payments', [CardController::class, 'showSpecificCardPayments'])->name('showSpecificCardPayments'); //for specific card payments
    Route::post('time-records-card-transaction/{id}', [CardController::class, 'timeRecordsCardTransaction'])->name('timeRecordsCardTransaction');
    Route::post('/save-cards', [CardController::class, 'saveCards'])->name('saveCards');
    Route::post('/import-cards',[CardController::class,'importCards'])->name('importCards');
    Route::get('/download-card-statement/{id}',[CardController::class,'downloadCardStatement']);

    Route::post('/credit-limit/{id}', [CardController::class, 'changeCardLimit'])->name('changeCardLimit');
    Route::post('/change-cardholder/{id}', [CardController::class, 'changeCardHolder'])->name('changeCardHolder');

    //Client Statement Page
    Route::get('/client-statements', [ClientStatementPageController::class, 'showClientStatements'])->name('showClientStatements');
    Route::post('/time-records-account-statement', [ClientStatementPageController::class, 'timeRecordsAccountStatement'])->name('timeRecordsAccountStatement');
    Route::get('/download-card-statement',[ClientStatementPageController::class,'userDownloadCardStatement'])->name('userDownloadCardStatement');

    //Client topUpHistory Page
    Route::get('/client-top-up-history', [ClientTopUpHistoryController::class, 'showClientsTopUpHistoryPage'])->name('showClientsTopUpHistoryPage');
    Route::post('/client-topup-request', [ClientTopUpHistoryController::class, 'clientTopUpRequest'])->name('clientTopUpRequest');

    //Client Create A Card
    Route::post('/client-create-a-card/invite-card-user', [ClientCreateACardController::class, 'clientInviteCardUser'])->name('clientInviteCardUser');
});

Route::get('/get-card-number/{id}', [CardController::class, 'decryptCardNumber'])->name('decryptCardNumber');

Route::get('/client-register', [ClientDashboardController::class, 'showClientRegister'])->name('showClientRegister');
Route::post('/client-register', [ClientDashboardController::class, 'storeClientRegistration'])->name('storeClientRegistration');
Route::get('/client-login', [ClientDashboardController::class, 'showClientLogin'])->name('showClientLogin');

Route::get('/client-register/invite-card-user/{id}', [ClientCreateACardController::class, 'showClientRegisterInviteCardUser'])->name('showClientRegisterInviteCardUser'); //added 28nov
Route::post('/client-register/invite-card-user', [ClientCreateACardController::class, 'submitClientRegisterInviteCardUser'])->name('submitClientRegisterInviteCardUser'); //added 28nov

require __DIR__ . '/auth.php';
