<?php

namespace App\Enum;

enum UserRoles: string
{
    case ADMIN = 'ROLE_ADMIN';
    case CUSTOMER = 'ROLE_CUSTOMER';
    case STAFF = 'ROLE_STAFF';
}
