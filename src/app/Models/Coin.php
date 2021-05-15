<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coin
{
    use HasFactory;

    protected $fillable = ['wallet_id','coin_id', 'name', 'symbol', 'amount', 'value_usd'];

}
