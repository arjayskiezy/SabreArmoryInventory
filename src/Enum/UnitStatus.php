<?php
namespace App\Enum;

enum UnitStatus: string
{
    case ACTIVE = 'ACTIVE';
    case DAMAGED = 'DAMAGED';
    case RETIRED = 'RETIRED';
}