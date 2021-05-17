<?php

namespace App\Services\Wallet;

use App\DataSource\Database\EloquentUserDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use Exception;

class PostWalletOpenService
{
    /**
     * @var EloquentWalletDataSource
     * @var EloquentUserDataSource
     */
    private EloquentWalletDataSource $eloquentWalletRepository;
    private EloquentUserDataSource $eloquentUserRepository;

    /**
     * PostWalletOpenService constructor.
     * @param EloquentWalletDataSource $eloquentWalletRepository
     * @param EloquentUserDataSource $eloquentUserRepository
     */
    public function __construct(
        EloquentWalletDataSource $eloquentWalletRepository,
        EloquentUserDataSource $eloquentUserRepository
    ) {
        $this->eloquentWalletRepository = $eloquentWalletRepository;
        $this->eloquentUserRepository = $eloquentUserRepository;
    }

    /**
     * @param string $user_id
     * @return string
     * @throws Exception
     */
    public function execute(string $user_id): string
    {
        if (!$this->eloquentUserRepository->existsByUserId($user_id)) {
            throw new Exception('User not found');
        }

        return $this->eloquentWalletRepository->createWalletByUserId($user_id);
    }
}
