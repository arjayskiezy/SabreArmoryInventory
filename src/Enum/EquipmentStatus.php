<?php
// src/Enum/EquipmentStatus.php
namespace App\Enum;

enum EquipmentStatus: string
{
    case AVAILABLE = 'AVAILABLE';
    case CHECKED_OUT = 'CHECKED_OUT';
    case MAINTENANCE = 'MAINTENANCE';
}