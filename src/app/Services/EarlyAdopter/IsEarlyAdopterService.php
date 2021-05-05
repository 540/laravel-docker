<?php

namespace App\Services\EarlyAdopter;

use App\DataSource\Database\EloquentUserDataSource;
use Exception;

class IsEarlyAdopterService
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
