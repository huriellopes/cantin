<?php

namespace App\Http\Controllers\Api\TransPeople;

use App\Http\Controllers\Controller;
use App\Services\Address\CreateAddressService;
use App\Services\TransPeople\CreateTransPeopleService;
use App\Traits\Utils;
use App\Http\Requests\TransPeople\TransPeopleRequest;
use App\Http\DTO\TransPeople\TransPeopleDTO;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateTransPeopleController extends Controller
{
    use Utils;

    /**
     * @param CreateAddressService $createAddressService
     * @param CreateTransPeopleService $createTransPeopleService
     */
    public function __construct(
        protected CreateAddressService $createAddressService,
        protected CreateTransPeopleService $createTransPeopleService,
    ){}

    /**
     * @param TransPeopleRequest $request
     * @return JsonResponse
     */
    public function __invoke(TransPeopleRequest $request) : JsonResponse
    {
        try {
            DB::beginTransaction();
                $params = TransPeopleDTO::from($request);

                $address = $this->createAddressService->create($params);

                $params->address_id = $address->id;

                $this->createTransPeopleService->create($params);
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
            return $this->returnResponse(
                false,
                Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                null,
                Response::HTTP_BAD_REQUEST,
                $e,
            );
        }
    }
}
