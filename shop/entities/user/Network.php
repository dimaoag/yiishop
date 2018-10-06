<?php
namespace shop\entities\user;

use yii\db\ActiveRecord;
use \Webmozart\Assert\Assert;

/**
 * @property integer $user_id
 * @property string $identity
 * @property integer $network
 */

class Network  extends  ActiveRecord{

    public static function create($network, $identity): self {
        Assert::notEmpty($network);
        Assert::notEmpty($identity);

        $item = new static();
        $item->network = $network;
        $item->identity = $identity;
        return $item;
    }

    public static function tableName()
    {
        return '{{%user_networks}}';
    }


}