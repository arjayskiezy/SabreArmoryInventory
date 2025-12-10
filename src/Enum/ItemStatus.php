<?php
// src/Enum/ItemStatus.php
namespace App\Enum;

enum ItemStatus: string
{
    case GOOD = 'GOOD';
    case MAINTENANCE = 'MAINTENANCE';
    case LOW_AMMO = 'LOW_AMMO';
    case URGENT = 'URGENT';
}
