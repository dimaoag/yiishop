<?php

namespace shop\forms\shop\Order;

use yii\base\Model;

class CustomerForm extends Model
{
    public $phone;
    public $name;

    public function rules(): array
    {
        return [
            [['phone', 'name'], 'required'],
            [['phone', 'name'], 'string', 'max' => 255],
        ];
    }
}