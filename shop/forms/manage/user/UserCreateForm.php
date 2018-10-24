<?php
namespace shop\forms\manage\user;

use shop\entities\user\User;
use yii\base\Model;

class UserCreateForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            [['username', 'email'], 'unique', 'targetClass' => User::class],
            ['email', 'email'],
            ['password', 'string', 'min' => 6],
        ];
    }

}