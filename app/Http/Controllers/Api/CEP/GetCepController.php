<?php

namespace App\Http\Controllers\Api\CEP;

use App\Http\Controllers\Controller;
use App\Http\DTO\CEP\GetCepDTO;
use App\Http\Requests\CEP\GetCepRequest;
use App\Http\Resources\CEP\GetCepResource;
use App\Services\CEP\GetCepService;
use App\Traits\MessagesDefaults;
use App\Traits\Utils;
use Exception;
use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetCepController extends Controller
{
    use Utils;

    /**
     * @param GetCepService $getCepService
     */
    public function __construct(
        protected GetCepService $getCepService
    ){}

    /**
     * @param GetCepRequest $request
     * @return JsonResponse
     */
    public function __invoke(GetCepRequest $request) : JsonResponse
    {
        try {
            $params = GetCepDTO::from($request);

            $cep = new GetCepResource($this->getCepService->getCep($params));

            return $this->returnResponse(
                true,
                null,
                $cep,
                Response::HTTP_OK
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
                MessagesDefaults::ERROR400,
                null,
                Response::HTTP_BAD_REQUEST,
                $e
            );
        }
    }
}
