<?php
namespace frontend\services\contact;

use frontend\forms\ContactForm;
use Yii;
use yii\mail\MailerInterface;

class ContactService
{
    private $supportEmail;
    private $adminEmail;
    private $mailer;

    public function __construct($supportEmail, $adminEmail, MailerInterface $mailer)
    {
        $this->supportEmail = $supportEmail;
        $this->adminEmail = $adminEmail;
        $this->mailer = $mailer;
    }

    public function send(ContactForm $form): void
    {
        $sent = $this->mailer->compose()
            ->setTo($this->adminEmail)
            ->setFrom($this->supportEmail)
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();
        if (!$sent){
            throw new \RuntimeException('Sending error!');
        }
    }
}