<?php

namespace shop\listeners\user;

use shop\services\newsletter\Newsletter;
use shop\entities\user\events\UserSignUpConfirmed;

class UserSignupConfirmedListener
{
    private $newsletter;

    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    public function handle(UserSignUpConfirmed $event): void
    {
        $this->newsletter->subscribe($event->user->email);
    }
}