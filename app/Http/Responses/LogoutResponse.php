<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutResponse implements Responsable
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        return redirect()->route('site.auth.login-cantin');
    }
}
