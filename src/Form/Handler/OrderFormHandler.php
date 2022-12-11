<?php


namespace App\Form\Handler;


use App\Entity\Order;
use App\Utils\Manager\OrderManager;


class OrderFormHandler
{

    /**
     * @var OrderManager
     */
    private $orderManager;

    /**
     * OrderFormHandler constructor.
     * @param OrderManager $orderManager
     */
    public function __construct(OrderManager $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function proccesEditForm(Order $order )
    {

        $this->orderManager->save($order);

        return $order;
    }
}