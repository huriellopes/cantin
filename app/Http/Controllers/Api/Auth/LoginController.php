<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Services\Auth\LoginService;
use App\Services\Auth\RegisterService;
use App\Services\BotWebhook\TelegramService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return AuthResource
     */
    public function login(LoginRequest $request) : AuthResource
    {
        $user = app(LoginService::class)
            ->HasLogin($request);

        return AuthResource::make($user);
    }

    /**
     * @param RegisterRequest $request
     * @return AuthResource
     */
    public function register(RegisterRequest $request) : AuthResource
    {
        try {
            $user = collect();//app(RegisterService::class)
                //->store($request);

            return AuthResource::make($user);
        } catch (\Exception $e) {
            Log::channel('telegram')->error($e->getMessage(), [
                'exception' => $e,
                'url' => request()?->fullUrl(),
                'method' => request()?->method(),
            ]);
        }
    }
}
