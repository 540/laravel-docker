<?php

namespace App\Http\Controllers;

use App\Http\Services\Adopter\IsEarlyAdopterService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class IsEarlyAdopterController extends BaseController
{
    /**
     * @var IsEarlyAdopterService
     */
    private $isEarlyAdopterService;

    /**
     * IsEarlyAdopterController constructor.
     * @param IsEarlyAdopterService $adopterService
     */
    public function __construct(IsEarlyAdopterService $adopterService)
    {
        $this->isEarlyAdopterService = $adopterService;
    }

    /**
     * @param string $id
     * @return JsonResponse
     *
     * Para obtener los parámetros de url, los pasamos en esta función como parámetro
     * @throws Exception
     */
    public function __invoke(string $id): JsonResponse
    {
        try{
            $ieEarlyAdopter =$this->isEarlyAdopterService->execute($id);
        }catch (Exception $ex){
            return response()->json([
                //'status' => $id,            // imprimir los datos que vienen de la url
                //'name' => $user->name,      // acceder a un atributo
                //'email' =>  $user->email,
                'error' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Obtener datos consulta
        return response()->json([
            //'status' => $id,            // imprimir los datos que vienen de la url
            //'name' => $user->name,      // acceder a un atributo
            //'email' =>  $user->email,
            'adopter' => $ieEarlyAdopter
        ], Response::HTTP_OK);
    }
}
