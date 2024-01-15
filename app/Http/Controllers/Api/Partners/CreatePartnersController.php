<?php

namespace App\Http\Controllers\Api\Partners;

use App\Archicture\Entities\Partners\Actions\CreatePartnersAction;
use App\Http\Controllers\Controller;
use App\Http\DTO\Partners\PartnersDTO;
use App\Http\Requests\Partners\PartnersRequest;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Throwable;

class CreatePartnersController extends Controller
{
    use Utils;

    /**
     * @param CreatePartnersAction $createPartnersAction
     */
    public function __construct(
        protected CreatePartnersAction $createPartnersAction,
    ){}

    /**
     * @param PartnersRequest $request
     * @return JsonResponse
     */
    public function __invoke(PartnersRequest $request) : JsonResponse
    {
        dd('teste');
        try {
            $params = PartnersDTO::from($request);
            dd($params);
            $this->createPartnersAction->execute($params);

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_CREATED],
                null,
                Response::HTTP_CREATED,
            );
        } catch (Exception|Throwable $e) {
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
