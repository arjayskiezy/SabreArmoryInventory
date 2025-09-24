<?php
// src/Enum/EquipmentStatus.php
namespace App\Enum;

enum EquipmentStatus: string
{
    case AVAILABLE = 'available';
    case CHECKED_OUT = 'checked_out';
    case MAINTENANCE = 'maintenance';
}