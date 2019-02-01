<?php
namespace shop\useCases\auth;


use shop\access\Rbac;
use shop\forms\auth\SignupForm;
use shop\entities\user\User;
use shop\services\RoleManager;
use shop\services\TransactionManager;
use yii\mail\MailerInterface;
use shop\repositories\UserRepository;

class SignupService{

    private $users;
    private $mailer;
    private $roles;
    private $transaction;

    public function __construct(UserRepository $users, MailerInterface $mailer, RoleManager $roles,  TransactionManager $transaction)
    {
        $this->mailer = $mailer;
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }


    public function signup(SignupForm $form)
    {
        $user = User::signup(
            $form->username,
            $form->email,
            $form->password
        );
        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        });

        $this->users->save($user);
        $sent = $this
            ->mailer
            ->compose(
                ['html' => 'auth/signup/emailConfirmToken-html', 'text' => 'auth/signup/emailConfirmToken-text'],
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
//        $this->newsletter->subscribe($user->email);
    }


}