<?php

namespace App\Application\EarlyAdopter;

use App\Application\UserDataSource\UserDataSource;
use Exception;

class IsEarlyAdopterService
{
    /**
     * @var UserDataSource
     */
    private $eloquentUserDataSource;

    /**
     * IsEarlyAdopterService constructor.
     * @param UserDataSource $eloquentUserDataSource
     */
    public function __construct(UserDataSource $eloquentUserDataSource)
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

        if ($user->getId() < 1000) {
            $isEarlyAdopter = true;
        }

        return $isEarlyAdopter;
    }
}
