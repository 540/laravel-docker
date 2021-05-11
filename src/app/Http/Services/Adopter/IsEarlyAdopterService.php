<?php


namespace App\Http\Services\Adopter;


use App\Infrastructure\Database\ElocuentUserDataSource;
use Illuminate\Support\Facades\DB;

class IsEarlyAdopterService
{
    /**
     * @var ElocuentUserDataSource
     */
    private $elocuentUserRepository;

    /**
     * isEarlyAdopterService constructor.
     * @param ElocuentUserDataSource $elocuentUserRepository
     */
    public function __construct(ElocuentUserDataSource $elocuentUserRepository)
    {
        $this->elocuentUserRepository = $elocuentUserRepository;
    }

    /**
     * @param string $id
     * @return bool
     * @throws \Exception
     */
    public function execute(string $id){
        // Hacer una consulta
        $user = $this->elocuentUserRepository->findById($id); // se puede acceder a los atributos de $user

        // Si no devuelve nada
        if($user == null){
            throw new \Exception('user not found');
        }

        $ieEarlyAdopter = false;
        if($user->id < 1000 ){
            $ieEarlyAdopter = true;
        }
        return $ieEarlyAdopter;
    }
}
