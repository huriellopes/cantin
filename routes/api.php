<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Routes\Api\AuthRoute;
use App\Http\Routes\Api\SiteApiRoute;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/up', function () {
    return response()->json([
        'status' => 200,
        'message' => 'API is working...',
    ], 200);
});

Route::get('/test-telegram', function() {
    $token = config('services.telegram_bot_api.token');
    $chatId = config('services.telegram_bot_api.chat_id');

    $message = "🚨 *Erro no CaNTIn* 🚨\n";
    $message .= "```\n" . "teste" . "\n```";
    $message .= "\n*Ambiente:* " . config('app.env');
    $message .= "\n*URL:* " . (request() ? request()->fullUrl() : 'N/A');
    $message .= "\n*IP:* " . (request() ? request()->ip() : 'N/A');
    $message .= "\nData e Hora: " . date('d/m/Y H:i:s');
    $message .= "\n*Método:* " . (request() ? request()->method() : 'N/A');

    $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'Markdown',
    ]);

    dd($response->body());
});

AuthRoute::api();
SiteApiRoute::api();

Route::middleware('auth:sanctum')
    ->get('/test', function () {
        return response()->json([
            'status' => 200,
            'message' => 'API is working...',
        ], 200);
    });
