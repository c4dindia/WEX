<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Api\AccountsAPIController;
use App\Http\Controllers\Api\CardsAPIController;
use App\Http\Controllers\Api\LoginAPiController;
use App\Http\Controllers\Api\StatementsAPIController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaybisWebhookController;
use App\Http\Controllers\WallesterNotificationEventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/register-account',[LoginAPiController::class,'registerAccount']);
Route::post('/login',[LoginAPiController::class,'createToken']);
// ->middleware(['check.key.for.login.api']); // neuro 1|AliizyUtGGVMVp31wwXEsP1ShnogUkf5VDVeL6IPcd1313be , waheguru 7|uPRDUS6Wl4zS8ty1q0Qwc9vLcQnJmnJNDJPZWibF8735373f

Route::middleware(['check.valid.token'])->group(function () {
    Route::get('/user', function (Request $request) {
        return Auth::user()->name;
    });
    Route::delete('/logout',[LoginAPiController::class,'logout']);

    // Client Accounts
    Route::post('/search',[AccountsAPIController::class,'search']);

    Route::post('/invite-for-card-request',[AccountsAPIController::class,'sendCardInvitation']);

    Route::get('/account-details',[AccountsAPIController::class,'accountDetails']); //nv 4|TLevyGA20U1AUacjDjBgCNVFphWGIpc8ErXCJiOpd4c9c451
    Route::get('/refresh-account',[AccountsAPIController::class ,'refreshAccountData']);
    Route::post('/update-account-limits',[AccountsAPIController::class,'updateAccountLimits']);
    Route::get('/download-account-statement',[AccountsAPIController::class,'downloadAccountStatement']);

    Route::get('/account-statements',[StatementsAPIController::class,'getAccountStatements']);
    Route::post('/account-statements/filtered',[StatementsAPIController::class,'filteredStatements']);

    Route::get('/cards',[CardsAPIController::class,'getCardsList']);
    Route::get('/card/{card_id}/details',[CardsAPIController::class,'cardDetails']);
    Route::get('/card/{card_id}/block',[CardsAPIController::class,'blockCard']);
    Route::get('/card/{card_id}/unblock',[CardsAPIController::class,'unblockCard']);
    Route::get('/card/{card_id}/close',[CardsAPIController::class,'closeCard']);
    Route::get('/card/{card_id}/download-card-statement',[CardsAPIController::class,'downloadCardStatement']);
    Route::post('/create-virtual-card',[CardsAPIController::class,'createVirtualCard']);
    Route::post('/card/{card_id}/card-limits',[CardsAPIController::class,'updateCardLimits']);
    Route::post('/card/{card_id}/update-card-name',[CardsAPIController::class,'updateCardName']);
    Route::post('/card/{card_id}/update-3ds-settings',[CardsAPIController::class,'update3DS']);
});

Route::post('/paybis/webhook', [PaybisWebhookController::class, 'handleWebhook']);
Route::post('/auth/token', [AuthController::class, 'generateToken']);
Route::post('/auth/api-token', [AuthController::class, 'generateApiToken']);
Route::post('/create-accounts', [AccountController::class, 'createAccount']);


Route::middleware('wallesterbasic.auth')->group(function () {

    Route::post('/v1/create-card-event', [WallesterNotificationEventController::class, 'handleNotification']);
    Route::post('/v1/notifications', [WallesterNotificationEventController::class, 'handleNotification']);
    Route::post('/v1/cards/{card_id}/encrypted-card-number', [WallesterNotificationEventController::class, 'getEncryptedCardNumber']);
    Route::post('/v1/cards', [WallesterNotificationEventController::class, 'createCard']);
    Route::post('/v1/cards/{card_id}/encrypted-pin', [WallesterNotificationEventController::class, 'getEncryptedPin']);

    Route::post('/v1/activate-card-event', [WallesterNotificationEventController::class, 'handleNotification']);
});

Route::post('/v1/decrypt-msg', [WallesterNotificationEventController::class, 'decryptCard']);

//for non-existing API routes
Route::any('{any}', function () {
    return response()->json([
        'status' => false,
        'message' => 'Resource not found check in api Url '
    ], 404);
})->where('any', '.*');
