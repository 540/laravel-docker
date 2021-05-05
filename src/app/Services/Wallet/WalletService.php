<?php


namespace App\Http\Services\Wallet;

use App\DataSource\Database\ElocuentWalletDataSource;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * @var ElocuentWalletDataSource
     */
    private $elocuentWalletRepository;

    /**
     * isEarlyAdopterService constructor.
     * @param ElocuentWalletDataSource $elocuentWalletRepository
     */
    public function __construct(ElocuentWalletDataSource $elocuentWalletRepository)
    {
        $this->elocuentWalletRepository = $elocuentWalletRepository;
    }

    /**
     * @param string $wallet_id
     * @return string
     * @throws \Exception
     */
    public function execute(string $wallet_id)
    {
        // Hacer una consulta
        $wallet = $this->elocuentWalletRepository->findById($wallet_id); // Se puede acceder a los atributos de $wallet

        // Si no devuelve nada
        if ($wallet == null) {
            throw new \Exception('Wallet not found');
        }

        return $wallet;
    }
}
