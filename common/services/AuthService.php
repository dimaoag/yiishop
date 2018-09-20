<?php
namespace common\services;

use common\repositories\UserRepository;
use common\entities\User;
use common\forms\LoginForm;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsername($form->username);
        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)){
            throw new \DomainException('Undefined user or password');
        }
        return $user;
    }

}