<?php
namespace common\bootstrap;

use yii\base\BootstrapInterface;
use frontend\services\auth\PasswordResetService;
use frontend\services\contact\ContactService;
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