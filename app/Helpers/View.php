<?php

namespace App\Helpers;

class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data);
        $file = dirname(__DIR__, 2) . '/views/' . str_replace('.', '/', $view) . '.php';
        if (file_exists($file)) require $file;
        else echo "View not found: $view";
    }
}
