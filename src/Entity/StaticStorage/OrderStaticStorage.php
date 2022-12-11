<?php
namespace App\Entity\StaticStorage;

class OrderStaticStorage
{
    //Создан
    public const ORDER_STATUS_CREATED = 0;
    //В процессе обработки
    public const ORDER_STATUS_PROCESSING = 1;
    //Собран
    public const ORDER_STATUS_COMPLECTED = 2;
    //Доставлен
    public const ORDER_STATUS_DELIVERED = 3;
    //Отменён
    public const ORDER_STATUS_DENIED = 4;

    /**
     * @return array|string[]
     */
    public static function getOrderStatusChoices():array
    {
         return [
             self::ORDER_STATUS_CREATED => 'Created',
             self::ORDER_STATUS_PROCESSING => 'processing',
             self::ORDER_STATUS_COMPLECTED => 'complected',
             self::ORDER_STATUS_DELIVERED => 'delivered',
             self::ORDER_STATUS_DENIED => 'denied',
         ];
    }
}
