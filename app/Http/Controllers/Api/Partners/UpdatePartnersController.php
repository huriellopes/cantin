<?php

namespace App\Http\Controllers\Api\Partners;

use App\Archicture\Entities\Partners\Actions\UpdatePartnersAction;
use App\Archicture\Entities\Partners\Models\Partner;
use App\Http\Controllers\Controller;
use App\Http\DTO\Partners\PartnersDTO;
use App\Http\Requests\Partners\PartnersRequest;
use App\Traits\Utils;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Exception;

class UpdatePartnersController extends Controller
{
    use Utils;

    /**
     * @param UpdatePartnersAction $updatePatnersAction
     */
    public function __construct(
        protected UpdatePartnersAction $updatePatnersAction,
    ){}

    /**
     * @param Partner $partner
     * @param PartnersRequest $request
     * @return JsonResponse
     */
    public function __invoke(Partner $partner, PartnersRequest $request) : JsonResponse
    {
        try {
            $params = PartnersDTO::from($request);

            $this->updatePatnersAction->execute($partner, $params);

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_OK],
                null,
                Response::HTTP_OK,
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
