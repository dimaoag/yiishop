<?php

namespace shop\entities\shop\order;

class CustomerData
{
    public $phone;
    public $name;

    public function __construct($phone, $name)
    {
        $this->phone = $phone;
        $this->name = $name;
    }
}