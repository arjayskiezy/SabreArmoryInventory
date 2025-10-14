<?php
namespace App\Enum;

enum UnitStatus: string
{
    case ACTIVE = 'active';
    case DAMAGED = 'damaged';
    case RETIRED = 'retired';
}