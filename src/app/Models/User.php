<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User
{
   private String $id;
   private String $name;
   private String $email;


    public function __construct(string $id)
    {
        $this->id = $id;
        $this->email = "";
        $this->name = "";
    }

    public function getId():string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }


}
