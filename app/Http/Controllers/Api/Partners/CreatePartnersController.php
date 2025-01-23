<?php

namespace App\Http\Controllers\Api\Partners;

use App\Http\Controllers\Controller;
use App\Http\DTO\Partners\PartnersDTO;
use App\Http\Requests\Partners\PartnersRequest;
use App\Services\Partners\CreatePartnersService;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Throwable;

class CreatePartnersController extends Controller
{
    use Utils;

    /**
     * @param CreatePartnersService $createPartnersService
     */
    public function __construct(
        protected CreatePartnersService $createPartnersService,
    ){}

    /**
     * @param PartnersRequest $request
     * @return JsonResponse
     */
    public function __invoke(PartnersRequest $request) : JsonResponse
    {
        try {
            $params = PartnersDTO::from($request);

            $this->createPartnersService->create($params);

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_CREATED],
                null,
                Response::HTTP_CREATED,
            );
        } catch (Exception|Throwable $e) {
            ds([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
            ])->danger();
            return $this->returnResponse(
                false,
                Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                null,
                Response::HTTP_BAD_REQUEST,
                $e
            );
        }
    }
}
