<?php
// src/Enum/ItemStatus.php
namespace App\Enum;

enum ItemStatus: string
{
    case GOOD = 'Good';
    case MAINTENANCE = 'Maintenance';
    case LOW_AMMO = 'Low Ammo';
    case URGENT = 'Urgent';
}
