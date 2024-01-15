<?php

namespace App\Http\Controllers\Api\PartnersEntities;

use App\Http\Controllers\Controller;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Throwable;

class ListPartnersEntitiesController extends Controller
{
    use Utils;

    public function __construct(){}

    public function __invoke() : JsonResponse
    {
        // TODO: Implement __invoke() method.
    }
}
