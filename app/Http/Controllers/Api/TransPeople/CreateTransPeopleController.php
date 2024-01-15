<?php

namespace App\Http\Controllers\Api\TransPeople;

use App\Archicture\Entities\Addresses\Actions\CreateAddressAction;
use App\Http\Controllers\Controller;
use App\Traits\Utils;
use App\Http\Requests\TransPeople\TransPeopleRequest;
use App\Http\DTO\TransPeople\TransPeopleDTO;
use App\Archicture\Entities\TransPeople\Actions\CreateTransPeopleAction;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateTransPeopleController extends Controller
{
    use Utils;

    /**
     * @param CreateAddressAction $createAddressAction
     * @param CreateTransPeopleAction $createTransPeopleAction
     */
    public function __construct(
        protected CreateAddressAction $createAddressAction,
        protected CreateTransPeopleAction $createTransPeopleAction,
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

                $address = $this->createAddressAction->execute($params);

                $params->address_id = $address->id;

                $this->createTransPeopleAction->execute($params);
            DB::commit();

            return $this->returnResponse(
                true,
                Response::$statusTexts[Response::HTTP_CREATED],
                null,
                Response::HTTP_CREATED,
            );
        } catch (Exception|Throwable $e) {
            DB::rollBack();
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
