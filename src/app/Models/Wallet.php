<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id'];

    public function coins(){
        return $this->belongsToMany(Coin::class,
            'wallet_coins',
            'wallet_id',
            'coin_id',
        )->withPivot(['amount'])->withPivot(['value_usd']);
    }

    public function user(){
        $this->belongsTo(User::class);
    }
}
