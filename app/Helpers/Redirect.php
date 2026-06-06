<?php

namespace App\Helpers;

class Redirect
{
    public static function to(string $path): void { header("Location: {$path}"); exit; }
    public static function back(): void { self::to($_SERVER['HTTP_REFERER'] ?? '/'); }
}
