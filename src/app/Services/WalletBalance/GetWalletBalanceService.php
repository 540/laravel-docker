<?php


namespace App\Services\WalletBalance;


use App\DataSource\API\EloquentCoinDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\ExpectationFailedException;

class GetWalletBalanceService
{
    private EloquentWalletDataSource $eloquentWalletDataSource;
    private EloquentCoinDataSource $eloquentCoinDataSource;

    public function __construct(EloquentWalletDataSource $eloquentDataSource, EloquentCoinDataSource $eloquentCoinDataSource)
    {
        $this->eloquentWalletDataSource = $eloquentDataSource;
        $this->eloquentCoinDataSource = $eloquentCoinDataSource;
    }

    public function execute($walletId):float
    {
        $wallet = $this->eloquentWalletDataSource->findWalletById($walletId);

        if($wallet == null){
            throw new Exception('a wallet with the specified ID was not found.');
        }

        $pastPrice = 0;
        $actualPrice = 0;

        foreach ($wallet->coins as $coin){
            $pastPrice += $coin->amount * $coin->value_usd;
            $actualCoin = $this->eloquentCoinDataSource->findCoinById($coin->coin_id);
            if($actualCoin == null){
                throw new Exception('a coin with the specified ID was no found.');
            }
            //;
            $actualPrice += $coin->amount * $actualCoin['price_usd'];
        }

        return $actualPrice - $pastPrice;
    }
}
