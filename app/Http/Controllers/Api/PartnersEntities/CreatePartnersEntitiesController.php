<?php

namespace App\Http\Controllers\Api\PartnersEntities;

use App\Archicture\Entities\Addresses\Actions\CreateAddressAction;
use App\Archicture\Entities\PartnersEntities\Actions\CreatePartnersEntitiesAction;
use App\Http\Controllers\Controller;
use App\Http\DTO\PartnersEntities\PartnersEntitiesDTO;
use App\Http\Requests\PartnersEntities\PartnersEntitiesRequest;
use App\Traits\Utils;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class CreatePartnersEntitiesController extends Controller
{
    use Utils;

    /**
     * @param CreatePartnersEntitiesAction $createPartnersEntitiesAction
     * @param CreateAddressAction $createAddressAction
     */
    public function __construct(
        protected CreatePartnersEntitiesAction $createPartnersEntitiesAction,
        protected CreateAddressAction $createAddressAction,
    ){}

    /**
     * @param PartnersEntitiesRequest $request
     * @return JsonResponse
     */
    public function __invoke(PartnersEntitiesRequest $request) : JsonResponse
    {
        try {
            DB::beginTransaction();
                $params = PartnersEntitiesDTO::from($request);

                $address = $this->createAddressAction->execute($params);

                $params->address_id = $address->id;

                $this->createPartnersEntitiesAction->execute($params);
            DB::commit();

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_CREATED],
                null,
                Response::HTTP_CREATED,
            );
        } catch (Exception|Throwable $e) {
            DB::rollBack();

            $this->loggingDatabase('create parterns entities', 'error', $e);

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
