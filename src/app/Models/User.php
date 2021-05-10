<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'email'];

    public function wallet(){
        $this->hasOne(Wallet::class);
    }
}
