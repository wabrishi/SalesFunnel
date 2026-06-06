<?php

namespace App\Middleware;

use App\Helpers\Session;

class CsrfMiddleware implements Middleware
{
    public function handle(): bool
    {
        $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (in_array(strtoupper($method), ['POST'])) {
            $token = $_POST['_csrf'] ?? null;
            $storedToken = Session::get('csrf_token');
            if (!$token || !$storedToken || !hash_equals($storedToken, $token)) {
                http_response_code(419); echo "CSRF Token Mismatch"; return false;
            }
        }
        return true;
    }
    public static function generateToken(): string
    {
        if (!Session::has('csrf_token')) Session::set('csrf_token', bin2hex(random_bytes(32)));
        return Session::get('csrf_token');
    }
    public static function csrfField(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(self::generateToken()) . '">';
    }
}
