<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletCoin extends Model
{
    use HasFactory;

    protected $fillable = ['wallet_id', 'coin_id', 'amount', 'value_usd'];
}
