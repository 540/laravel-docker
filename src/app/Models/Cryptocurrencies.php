<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cryptocurrencies extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id_transaction",
        "id_coin",
        "usd_buyed_amount",
        "buyed_coins_amount",
        "buyed_coins_usd_price",
        "operation",
        "created_at",
        "updated_at",
        "id_wallet",
        "price_usd"
    ];
}
