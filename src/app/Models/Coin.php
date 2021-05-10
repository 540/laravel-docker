<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coin extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'wallet_id', 'coin_id', 'name', 'symbol', 'amount', 'value_usd'];

    public function wallets()
    {
        return $this->belongsTo(Wallet::class);
    }
}
