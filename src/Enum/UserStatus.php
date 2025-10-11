<?php
// src/Enum/UserStatus.php
namespace App\Enum;

enum UserStatus: string
{
    case COMMISSIONED = 'Commissioned';
    case NON_COMMISSIONED = 'Non-Commissioned';
    case ENLISTED = 'Enlisted';
}