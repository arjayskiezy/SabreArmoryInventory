<?php
// src/Enum/RequestStatus.php
namespace App\Enum;

enum RequestStatus: string
{
    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case DENIED = 'DENIED';
    case FULFILLED = 'FULFILLED';
    case CANCELLED = 'CANCELLED';
}