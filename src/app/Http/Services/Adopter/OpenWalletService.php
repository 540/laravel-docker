<?php


namespace App\Http\Services\Adopter;


use App\Infrastructure\Database\WalletDataSource;

class OpenWalletService
{
    /**
     * @var WalletDataSource
     */
    private $walletRepository;

    /**
     * isEarlyAdopterService constructor.
     * @param WalletDataSource $walletDataSource
     */
    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletRepository = $walletDataSource;
    }

    /**
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object
     * @throws \Exception
     */
    public function execute($id){
        if($id == null){
            throw new \Exception('Bad request error');
        }
        // Hacer una consulta
        $wallet = $this->walletRepository->insertById($id);
        // Si no devuelve nada
        if($wallet == null){
            throw new \Exception('Bad request error');
        }
        return $wallet;
    }
}
