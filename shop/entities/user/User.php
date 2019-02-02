<?php
namespace shop\entities\user;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\AggregateRoot;
use shop\entities\user\events\UserSignUpConfirmed;
use shop\entities\user\events\UserSignUpRequested;
use Yii;
use shop\entities\EventTrait;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $email_confirm_token
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $phone
 *
 * @property Network[] $networks
 * @property WishlistItem[] $wishlistItems
 */
class User extends ActiveRecord implements IdentityInterface, AggregateRoot
{
    use EventTrait;

    const STATUS_WAIT = 0;
    const STATUS_ACTIVE = 10;

    public static function create(string $username, string $phone, string $email, string $password):self
    {
        $user = new User();
        $user->username = $username;
        $user->phone = $phone;
        $user->email = $email;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->auth_key = Yii::$app->security->generateRandomString();
        return $user;
    }


    public function edit(string $username, string $phone, string $email):void
    {
        $this->username = $username;
        $this->phone = $phone;
        $this->email = $email;
        $this->updated_at = time();
    }

//    public function requestPhoneChange($phone): void
//    {
//        if (!empty($this->new_phone_confirm_expire) && $this->new_phone_confirm_expire > time()){
//            throw \DomainException('Token is already sent.');
//        }
//
//        $this->new_phone = $phone;
//        $this->new_phone_confirm_token = random_int(10000, 99999);
//        $this->new_phone_confirm_expire = time() + 180;
//        $this->new_phone_confirm_limit = 3;
//    }
//
//    public function confirmPhoneChange($token): bool
//    {
//        if (empty($this->new_phone_confirm_token)){
//            throw \DomainException('Token empty');
//        }
//
//        if ($token === $this->new_phone_confirm_token){
//            $this->phone = $this->new_phone;
//            $this->new_phone = null;
//            $this->new_phone_confirm_token = null;
//            return true;
//        }
//
//        if ($this->new_phone_confirm_limit <= 0){
//            throw \DomainException('Try again request phone ...');
//        }
//
//        $this->new_phone_confirm_limit--;
//        return false;
//    }


    public static function signup(string $username, string $email, string $password):self
    {
        $user = new static();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->created_at = time();
        $user->status = self::STATUS_WAIT;
        $user->generateEmailConfirmToken();
        $user->generateAuthKey();
        $user->recordEvent(new UserSignUpRequested($user));
        return $user;
    }


    public function confirmSignup()
    {
        if (!$this->isWait()){
            throw new \DomainException('User is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->removeEmailConfirmToken();
//        $this->recordEvent(new UserSignUpConfirmed($this));
    }

    public static function signupByNetwork($network, $identity) :self
    {
        $user = new User();
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->networks = [Network::create($network, $identity)];
        return $user;
    }

    public function attachNetwork($network, $identity):void
    {
        $networks = $this->networks;
        foreach ($networks as $current){
            if ($current->isFor($network, $identity)){
                throw new \DomainException('Network is already attached.');
            }
        }
        $networks[] = Network::create($network, $identity);
        $this->networks = $networks;
    }

    public function requestPasswordReset() :void
    {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)){
            throw new \DomainException('Password resetting is already requested.');
        }
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function resetPassword($password) :void
    {
        if (empty($this->password_reset_token)){
            throw new \DomainException('Password resetting is not requested.');
        }
        $this->setPassword($password);
        $this->password_reset_token = null;
    }


    public function isActive() :bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isWait() :bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function addToWishList($productId): void
    {
        $items = $this->wishlistItems;
        foreach ($items as $item) {
            if ($item->isForProduct($productId)) {
                throw new \DomainException('Item is already added.');
            }
        }
        $items[] = WishlistItem::create($productId);
        $this->wishlistItems = $items;
    }

    public function removeFromWishList($productId): void
    {
        $items = $this->wishlistItems;
        foreach ($items as $i => $item) {
            if ($item->isForProduct($productId)) {
                unset($items[$i]);
                $this->wishlistItems = $items;
                return;
            }
        }
        throw new \DomainException('Item is not found.');
    }


    public function getWishlistItems(): ActiveQuery
    {
        return $this->hasMany(WishlistItem::class, ['user_id' => 'id']);
    }

    public function getNetworks() :ActiveQuery
    {
        return $this->hasMany(Network::className(), ['user_id' => 'id']);
    }

//    public function getBuyerProfile() :ActiveQuery
//    {
//        return $this->hasOne(Buyer::class, ['user_id' => 'id']);
//    }
//
//    public function getDealerProfile() :ActiveQuery
//    {
//        return $this->hasOne(Dealer::class, ['user_id' => 'id']);
//    }


    public static function tableName()
    {
        return '{{%users}}';
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['networks', 'wishlistItems'],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }


    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }


    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }


    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }


    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }


    public function getAuthKey()
    {
        return $this->auth_key;
    }


    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    private function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }


    private function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }


    private function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }


    private function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    private function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    private function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }










}
