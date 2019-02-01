<?php
namespace shop\forms\manage\user;

use shop\entities\user\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserCreateForm extends Model
{
    public $username;
    public $phone;
    public $email;
    public $password;
    public $role;

    public function rules(): array
    {
        return [
            [['username', 'email', 'role'], 'required'],
            ['email', 'email'],
            [['username', 'email', 'phone'], 'string', 'max' => 255],
            [['username', 'email', 'phone'], 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 6],
        ];
    }


    public function rolesList(): array
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }

}