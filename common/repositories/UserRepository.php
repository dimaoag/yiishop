<?php
namespace common\repositories;

use common\entities\User;

class UserRepository
{
    public function save(User $user): void
    {
        if (!$user->save()){
            throw new \RuntimeException('Saving error.');
        }
    }

    public function existByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    public function getUserByEmail(string $email): User
    {
        return $this->getUserBy(['email'=> $email]);
    }

    public function getUserByPasswordResetToken(string $token): User
    {
        return $this->getUserBy(['password_reset_token'=> $token]);
    }

    public function getUserByConfirmToken(string $token): User
    {
        return $this->getUserBy(['email_confirm_token'=> $token]);
    }

    private function getUserBy(array $condition): User
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()){
            throw new NotFoundException('User not found.');
        }
        return $user;
    }


}