<?php

namespace shop\useCases\manage\shop;

use shop\entities\shop\order\CustomerData;
use shop\entities\shop\order\DeliveryData;
use shop\forms\manage\shop\order\OrderEditForm;
use shop\repositories\shop\DeliveryMethodRepository;
use shop\repositories\shop\OrderRepository;

class OrderManageService
{
    private $orders;
    private $deliveryMethods;

    public function __construct(OrderRepository $orders, DeliveryMethodRepository $deliveryMethods)
    {
        $this->orders = $orders;
        $this->deliveryMethods = $deliveryMethods;
    }

    public function edit($id, OrderEditForm $form): void
    {
        $order = $this->orders->get($id);

        $order->edit(
            new CustomerData(
                $form->customer->phone,
                $form->customer->name
            ),
            $form->note
        );

        $order->setDeliveryInfo(
            $this->deliveryMethods->get($form->delivery->method),
            new DeliveryData(
                $form->delivery->index,
                $form->delivery->address
            )
        );

        $this->orders->save($order);
    }

    public function remove($id): void
    {
        $order = $this->orders->get($id);
        $this->orders->remove($order);
    }
}