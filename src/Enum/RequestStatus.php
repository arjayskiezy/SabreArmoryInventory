<?php
// src/Enum/RequestStatus.php
namespace App\Enum;

enum RequestStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case DENIED = 'denied';
    case FULFILLED = 'fulfilled';
    case CANCELLED = 'cancelled';
}