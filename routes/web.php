<?php

use App\Http\Routes\Web\SiteRoute;
use Illuminate\Support\Carbon;

SiteRoute::web();

Route::get('/teste', function () {
    $cutOffDate = null;
    !empty($cutOffDate) ? $cutOffDate = Carbon::parse($cutOffDate)->endOfDay() : $cutOffDate = now()->endOfDay();

    dd($cutOffDate->format('Y-m-d H:i:s'));
});

//require __DIR__.'/auth.php';
