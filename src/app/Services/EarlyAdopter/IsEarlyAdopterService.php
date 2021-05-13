<?php

namespace App\Services\EarlyAdopter;

use App\DataSource\Database\EloquentUser540DataSource;
use Exception;

class IsEarlyAdopterService
{
    /**
     * @var EloquentUser540DataSource
     */
    private $eloquentUserDataSource;

    /**
     * IsEarlyAdopterService constructor.
     * @param EloquentUser540DataSource $eloquentUserDataSource
     */
    public function __construct(EloquentUser540DataSource $eloquentUserDataSource)
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
