<?php
namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;
use yii\mail\MailerInterface;
use Yii;

class PasswordResetService
{
    private $supportEmail;
    private $mailer;


    public function __construct($supportEmail, MailerInterface $mailer)
    {
           $this->supportEmail = $supportEmail;
           $this->mailer = $mailer;
    }

    public function request(PasswordResetRequestForm $form)
    {
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $form->email,
        ]);
        if (!$user){
            throw new \DomainException('User is not found.');
        }
        $user->requestPasswordReset();
        if (!$user->save()){
            throw new \RuntimeException('Saving error.');
        }
        $send = $this
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom($this->supportEmail)
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
        $this->_user = User::findByPasswordResetToken($token);
        if (!User::findByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }



    public function reset(string $token, ResetPasswordForm $form)
    {
        $user = User::findByPasswordResetToken($token);
        if (!$user){
            throw new \DomainException('User is not found.');
        }
        $user->resetPassword($form->password);
        if (!$user->save()){
            throw new \RuntimeException('Saving error.');
        }
    }

}