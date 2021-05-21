<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';

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
        "id_wallet"
    ];

    public $timestamps = false;
}
