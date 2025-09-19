<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        return redirect()->route('site.auth.login');
    }
}
