<?php

namespace App\Http\Controllers\Api\Terreiros;

use App\Http\Controllers\Controller;
use App\Http\DTO\Terreiro\TerreiroDTO;
use App\Http\Requests\Terreiros\TerreirosRequest;
use App\Services\Address\CreateAddressService;
use App\Services\Terreiros\CreateTerreirosService;
use App\Traits\Utils;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Exception;
use Illuminate\Http\JsonResponse;

class CreateTerreirosController extends Controller
{
    use Utils;

    /**
     * @param CreateTerreirosService $createTerreirosService
     * @param CreateAddressService $createAddressService
     */
    public function __construct(
        protected CreateTerreirosService $createTerreirosService,
        protected CreateAddressService $createAddressService,
    ){}

    public function __invoke(TerreirosRequest $request) : JsonResponse
    {
        try {
            DB::beginTransaction();

                $params = TerreiroDTO::from($request);

                $address = $this->createAddressService->create($params);

                $params->address_id = $address->id;

                $terreiro = $this->createTerreirosService->create($params);

            DB::commit();

            return $this->returnResponse(
                true,
                'Terreiro Cadastrado com sucesso.',
                array(
                    'id' => $terreiro->id,
                ),
                Response::HTTP_CREATED
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
