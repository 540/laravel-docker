<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    use HasFactory;

    protected $table = 'coins';
    protected $fillable = ['coin_id', 'name', 'symbol'];
    public $timestamps = false;
}
