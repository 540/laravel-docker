<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletCoin extends Model
{
    use HasFactory;

    protected $table = 'walletscoins';
    protected $fillable = ['wallet_id', 'coin_id', 'amount'];
    public $timestamps = false;
}
