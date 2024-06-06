<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CardController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/payments', [PaymentController::class, 'createPayment']);
Route::get('/payments', [PaymentController::class, 'listPayments']);
Route::put('/payments/{transaction_id}', [PaymentController::class, 'updatePaymentStatus']);
Route::post('/payments/notification', [PaymentController::class, 'paymentNotification']);
Route::post('/cards', [CardController::class, 'store']);
Route::post('/getCard', [CardController::class, 'getCard'])->name('getCard');
Route::post('/cards/check-balance', [CardController::class, 'checkBalance']);
