<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\entities\User;


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
