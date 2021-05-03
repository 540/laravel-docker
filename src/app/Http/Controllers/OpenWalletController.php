<?php

namespace App\Http\Controllers;

use App\Infrastructure\Database\WalletDataSource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class OpenWalletController
 * @package App\Http\Controllers
 */
class OpenWalletController extends BaseController
{
    /**
     * @var WalletDataSource
     */
    private $wallet;

    /**
     * OpenWalletController constructor.
     */
    public function __construct()
    {
        $this->wallet = new WalletDataSource();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Recibir los datos en json de postman
        $id = $request->input("user_id");

        // Guardar los datos
        $wallet_id = $this->wallet->insertById($id);

        // Devolver json
        return response()->json([
            'wallet_id' => $wallet_id
        ], Response::HTTP_OK);
    }
}
