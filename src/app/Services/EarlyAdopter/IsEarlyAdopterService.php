<?php


namespace App\Services\EarlyAdopter;


use App\Infraestructure\Database\ElocuentUserRepository;
use App\Services\ServiceManager;
use Egulias\EmailValidator\Exception\ExpectingAT;

class IsEarlyAdopterService
{
    private $elocuentUserRepository;

    public function __constructor(ElocuentUserRepository $elocuentUserDataSource){
        $this->elocuentUserRepository = $elocuentUserDataSource;
    }

    public function execute(string $email){

        $user = $this->elocuentUgserRepository->findByEmail($email);
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
