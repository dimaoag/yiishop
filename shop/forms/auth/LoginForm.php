<?php
namespace shop\forms\auth;


use yii\base\Model;


class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;


    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        ];
    }

}
