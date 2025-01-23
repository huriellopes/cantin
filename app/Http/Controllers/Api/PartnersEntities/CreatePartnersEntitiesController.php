<?php

namespace App\Http\Controllers\Api\PartnersEntities;

use App\Http\Controllers\Controller;
use App\Http\DTO\PartnersEntities\PartnersEntitiesDTO;
use App\Http\Requests\PartnersEntities\PartnersEntitiesRequest;
use App\Services\Address\CreateAddressService;
use App\Services\PartnersEntities\CreatePartnersEntitiesService;
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
     * @param CreatePartnersEntitiesService $createPartnersEntitiesService
     * @param CreateAddressService $createAddressService
     */
    public function __construct(
        protected CreatePartnersEntitiesService $createPartnersEntitiesService,
        protected CreateAddressService $createAddressService,
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

                $address = $this->createAddressService->create($params);

                $params->address_id = $address->id;

                $this->createPartnersEntitiesService->create($params);
            DB::commit();

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_CREATED],
                null,
                Response::HTTP_CREATED,
            );
        } catch (Exception|Throwable $e) {
            DB::rollBack();
            ds([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
            ])->danger();

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
