<?php

namespace App\Helpers;

class Session
{
    public static function start(): void { if (session_status() === PHP_SESSION_NONE) session_start(); }
    public static function set(string $key, mixed $value): void { $_SESSION[$key] = $value; }
    public static function get(string $key, mixed $default = null): mixed { return $_SESSION[$key] ?? $default; }
    public static function has(string $key): bool { return isset($_SESSION[$key]); }
    public static function destroy(): void { session_destroy(); $_SESSION = []; }
    public static function flash(string $key, mixed $value): void { $_SESSION['flash'][$key] = $value; }
    public static function getFlash(string $key, mixed $default = null): mixed
    {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        return $default;
    }
}
