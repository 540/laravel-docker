<?php

namespace App\Services\EarlyAdopter;

use App\Infraestructure\Database\EloquentUserRepository;

class IsEarlyAdopterService
{
    private $elocuentUserRepository;

    public function __constructor(EloquentUserRepository $elocuentUserDataSource){
        $this->elocuentUserRepository = $elocuentUserDataSource;
    }

    public function execute(string $email){

        $user = $this->elocuentUserRepository->findByEmail($email);
        if($user == null){
            throw new \Exception('User not found');
        }
        $isEarlyAdopter = false;
        if($user->id < 1000){
            $isEarlyAdopter = true;
        }
        return $isEarlyAdopter;
    }
}
