<?php
namespace App\Entity\StaticStorage;

class UserStaticStorage
{
    //Admin
    public const USER_ROLE_ADMIN = 'ROLE_ADMIN';
    //User
    public const USER_ROLE_USER = 'ROLE_USER';

    public const USER_ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';



    /**
     * @return array|string[]
     */
    public static function getUserRolesChoices():array
    {
        return [
            self::USER_ROLE_ADMIN => 'Admin',
            self::USER_ROLE_USER => 'User',
            self::USER_ROLE_SUPER_ADMIN => 'SUPER Admin',
        ];
    }
}