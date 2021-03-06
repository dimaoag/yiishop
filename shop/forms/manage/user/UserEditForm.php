<?php
namespace shop\forms\manage\user;

use shop\entities\user\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserEditForm extends Model
{
    public $username;
    public $phone;
    public $email;
    public $role;

    public $_user;

    public function __construct(User $user, array $config = [])
    {
        $this->username = $user->username;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $roles = Yii::$app->authManager->getRolesByUser($user->id);
        $this->role = $roles ? reset($roles)->name : null;
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['username', 'email', 'role'], 'required'],
            ['email', 'email'],
            [['email', 'phone'], 'string', 'max' => 255],
            [['username', 'email', 'phone'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
        ];
    }

    public function rolesList(): array
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }

}