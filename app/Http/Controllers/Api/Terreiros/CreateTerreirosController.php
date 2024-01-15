<?php

namespace App\Http\Controllers\Api\Terreiros;

use App\Archicture\Entities\Addresses\Actions\CreateAddressAction;
use App\Archicture\Entities\Terreiros\Actions\CreateTerreirosAction;
use App\Http\Controllers\Controller;
use App\Http\DTO\Terreiro\TerreiroDTO;
use App\Http\Requests\Terreiros\TerreirosRequest;
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
     * @param CreateTerreirosAction $createTerreirosAction
     * @param CreateAddressAction $createAddressAction
     */
    public function __construct(
        protected CreateTerreirosAction $createTerreirosAction,
        protected CreateAddressAction $createAddressAction,
    ){}

    public function __invoke(TerreirosRequest $request) : JsonResponse
    {
        try {
            DB::beginTransaction();
//            dd($request->all());
            $params = TerreiroDTO::from($request);

            $address = $this->createAddressAction->execute($params);

            $params->address_id = $address->id;

            $terreiro = $this->createTerreirosAction->execute($params);

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
            dd($e->getMessage());

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
