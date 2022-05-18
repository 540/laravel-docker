<?php

namespace App\Application\CoinDataSource;


use App\Domain\Coin;
use Exception;
use Illuminate\Support\Facades\Cache;
use App\Domain\Wallet;
class BuyCoinDataSourceFunction implements BuyCoinDataSource
{
    /**
     * @throws Exception
     */
    public function findByCoinId(string $coin_id,string $wallet_id,float $amount_usd)
    {
        $url = 'https://api.coinlore.net/api/ticker/?id=' . $coin_id;

        $ch = curl_init( $url );
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER,0);

        $data = json_decode(curl_exec($ch));
        curl_close($ch);
        if($data == null){
            throw new Exception("A coin with the specified ID was not found.");
        }


        $esta = false;
        $wallet = Cache::get($wallet_id);
        if($wallet != null) {
            $coin2 = $wallet->getCoins();
            foreach ($coin2 as $k=>$coin){
                if($coin->getCoinId() == $coin_id){
                    $coin->setAmount($coin->getAmount()+$amount_usd);
                    $esta = true;
                }
            }
            $wallet->setCoins($coin2);
            Cache::put($wallet_id, $wallet);
        }
        if(!$esta) {
            $data = $data[0];
            $name = $data->name;
            $symbol = $data->symbol;
            $amount = $amount_usd;
            $value_usd = floatval($data->price_usd);
            $name_id = $data->nameid;
            $rank = $data->rank;

            $Coin = new Coin($amount,$coin_id,$name,$name_id,$rank,$symbol,$value_usd);
            $coin2[] = $Coin;
        }
        return  "successful operation";
    }

    /**
     * @throws Exception
     */
    public function SellCoin(string $coin_id, string $wallet_id, float $amount_usd)
    {
        $url = 'https://api.coinlore.net/api/ticker/?id=' . $coin_id;

        $ch = curl_init( $url );
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER,0);

        $data = json_decode(curl_exec($ch));
        curl_close($ch);
        if($data == null){
            throw new Exception("A coin with the specified ID was not found.");
        }

        $esta = false;
        $wallet = Cache::get($wallet_id);
        if($wallet != null) {
            $coin2 = $wallet->getCoins();
            foreach ($coin2 as $k=>$coin){
                if($coin->getCoinId() == $coin_id){
                    $coin->setAmount($coin->getAmount()-$amount_usd);
                    if($coin->getAmount() < 0){
                        unset($coin2[$k]);
                    }
                    $esta = true;
                }
            }

            $wallet->setCoins($coin2);
            Cache::put($wallet_id, $wallet);
        }
        if(!$esta) {
            throw new Exception("A coin with the specified ID was not found.");
        }
        return  "successful operation";
    }
}
