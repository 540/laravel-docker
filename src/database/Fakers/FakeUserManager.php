<?php


use App\Models\User;
use Illuminate\Support\Facades\DB;

class FakeUserManager
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function insertFakeUser(){
        DB::table('users')->insert(
            ['id' => $this->user->getId(),
                'name' => $this->user->getName(),
                'email' => $this->user->getEmail()]);
    }

    public function deleteFakeUser(){
        DB::table('users')->where('id', '=', $this->user->getId())->delete();
    }
}
