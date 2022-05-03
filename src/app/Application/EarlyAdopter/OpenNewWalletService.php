<?php

namespace App\Application\EarlyAdopter;

use App\Application\UserDataSource\UserDataSource;

class OpenNewWalletService
{
    /**
     * @var UserDataSource
     */
    private $userDataSource;

    /**
     * IsEarlyAdopterService constructor.
     * @param UserDataSource $userDataSource
     */
    public function __construct(UserDataSource $userDataSource)
    {
        $this->userDataSource = $userDataSource;
    }

    public function execute(string $id): bool
    {
        $userExists = $this->userDataSource->findById($id);

        return $userExists;
    }
}
