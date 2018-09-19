<?php
namespace frontend\services\auth;

use frontend\forms\SignupForm;
use common\entities\User;
use yii\mail\MailerInterface;
class SignupService{

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    public function signup(SignupForm $form)
    {
        $user = User::signup(
            $form->username,
            $form->email,
            $form->password
        );
        if (!$user->save()){
            throw new \RuntimeException('Saving Error!');
        }
        $sent = $this
            ->mailer
            ->compose(
                ['html' => 'emailConfirmToken-html', 'text' => 'emailConfirmToken-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Signup confirm for ' . 'My App')
            ->send();
        if (!$sent){
            throw new \RuntimeException('Email sending error.');
        }
    }

    public function confirm($token)
    {
        if (empty($token)){
            throw new \DomainException('Empty confirm token');
        }
        $user = User::findOne(['email_confirm_token' => $token]);
        if (!$user){
            throw new \DomainException('User is not found!');
        }
        $user->confirmSignup();
        if (!$user->save()){
            throw new \RuntimeException('Saving error.');
        }
    }

}