<?php
namespace shop\useCases\manage;

use shop\entities\user\User;
use shop\repositories\UserRepository;
use shop\forms\manage\user\UserCreateForm;
use shop\forms\manage\user\UserEditForm;

class UserManegeService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );
        $this->repository->save($user);
        return $user;
    }


    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->getUserById($id);
        $user->edit(
            $form->username,
            $form->email
        );
        $this->repository->save($user);
    }





}