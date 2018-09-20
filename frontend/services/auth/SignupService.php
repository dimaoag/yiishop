<?php
namespace frontend\services\auth;

use frontend\forms\SignupForm;
use common\entities\User;
use yii\mail\MailerInterface;
use common\repositories\UserRepository;
class SignupService{

    private $mailer;
    private $users;

    public function __construct(UserRepository $users, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->users = $users;
    }


    public function signup(SignupForm $form)
    {
        $user = User::signup($form->username, $form->email, $form->password);
        $this->users->save($user);
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
        $user = $this->users->getUserByConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);

    }


}