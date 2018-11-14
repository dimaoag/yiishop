<?php
namespace common\bootstrap;

use yii\base\BootstrapInterface;
use shop\useCases\auth\PasswordResetService;
use shop\useCases\ContactService;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app){
            return $app->mailer;
        });

        $container->setSingleton(PasswordResetService::class);
        //MailerInterface::class по дефолту автоматически передается как параметр

        $container->setSingleton(ContactService::class, [],[
            $app->params['adminEmail']
            //MailerInterface::class по дефолту автоматически передается как параметр
        ]);
    }
}