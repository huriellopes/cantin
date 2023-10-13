<?php

namespace App\Http\Controllers\Api\CEP;

use App\Archicture\Entities\CEP\Actions\GetCepAction;
use App\Archicture\Generics\TraitsGenerals\MessagesDefaults;
use App\Http\Controllers\Controller;
use App\Http\DTO\CEP\GetCepDTO;
use App\Http\Requests\CEP\GetCepRequest;
use App\Http\Resources\CEP\GetCepResource;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetCepController extends Controller
{
    use Utils;

    /**
     * @param GetCepAction $getCepAction
     */
    public function __construct(
        protected GetCepAction $getCepAction
    ){}

    /**
     * @param GetCepRequest $request
     * @return JsonResponse
     */
    public function __invoke(GetCepRequest $request) : JsonResponse
    {
        try {
            $params = GetCepDTO::from($request);

            $cep = new GetCepResource($this->getCepAction->execute($params));

            return $this->returnResponse(
                true,
                null,
                $cep,
                Response::HTTP_OK
            );
        } catch (Exception $exception) {
            return $this->returnResponse(
                false,
                MessagesDefaults::ERROR400,
                null,
                Response::HTTP_BAD_REQUEST,
                $exception
            );
        }
    }
}
