<?php


namespace App\Services\CoinBuy;


use App\DataSource\Database\EloquentUserDataSource;

class coinBuyerService
{
    /**
     * @var EloquentUserDataSource
     */
    private $eloquentUserDataSource;

    /**
     * IsEarlyAdopterService constructor.
     * @param EloquentUserDataSource $eloquentUserDataSource
     */
    public function __construct(EloquentUserDataSource $eloquentUserDataSource)
    {
        $this->eloquentUserDataSource = $eloquentUserDataSource;
    }

    /**
     * @param string $email
     * @return bool
     * @throws Exception
     */
    public function execute(string $email): bool
    {
        $user = $this->eloquentUserDataSource->findByEmail($email);

        $isEarlyAdopter = false;

        if ($user->id < 1000) {
            $isEarlyAdopter = true;
        }

        return $isEarlyAdopter;
    }
}
