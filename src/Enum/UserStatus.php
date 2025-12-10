<?php
// src/Enum/UserStatus.php
namespace App\Enum;

enum UserStatus: string
{
    case COMMISSIONED = 'COMMISSIONED';
    case NON_COMMISSIONED = 'Non-NON_COMMISSIONED';
    case ENLISTED = 'ENLISTED';
}