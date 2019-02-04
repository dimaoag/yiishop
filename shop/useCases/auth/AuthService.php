<?php
namespace shop\useCases\auth;

use shop\repositories\UserRepository;
use shop\entities\user\User;
use shop\forms\auth\LoginForm;

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