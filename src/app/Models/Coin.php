<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'symbol'];

    public function wallets(){
        return $this->belongsToMany(Wallet::class,
            'wallet_coins',
            'coin_id',
            'wallet_id'
        );
    }
}
