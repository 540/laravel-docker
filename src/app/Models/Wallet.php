<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet
{
    use HasFactory;

    protected $fillable = ['id', 'user_id'];

}
