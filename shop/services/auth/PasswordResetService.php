<?php
namespace shop\services\auth;

use shop\forms\auth\PasswordResetRequestForm;
use shop\forms\auth\ResetPasswordForm;
use yii\mail\MailerInterface;
use shop\repositories\UserRepository;

class PasswordResetService
{
    private $mailer;
    private $users;


    public function __construct(UserRepository $users, MailerInterface $mailer)
    {
           $this->mailer = $mailer;
           $this->users = $users;
    }

    public function request(PasswordResetRequestForm $form)
    {
        $user = $this->users->getUserByEmail($form->email);

        if (!$user->isActive()){
            throw new \DomainException('User is not active.');
        }

        $user->requestPasswordReset();
        $this->users->save($user);

        $send = $this
            ->mailer
            ->compose(
                ['html' => 'auth/reset/passwordResetToken-html', 'text' => 'auth/reset/passwordResetToken-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Password reset for ' . 'My Application')
            ->send();
        if (!$send){
            throw new \RuntimeException('Sending error.');
        }
    }


    public function validateToken($token)
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }
        if (!$this->users->existByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }


    public function reset(string $token, ResetPasswordForm $form)
    {
        $user = $this->users->getUserByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->users->save($user);
    }



}