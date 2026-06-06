<?php

namespace App\Middleware;

use App\Helpers\Session;
use App\Helpers\Redirect;

class AuthMiddleware implements Middleware
{
    public function handle(): bool
    {
        if (!Session::has('user_id')) {
            Session::flash('error', 'Please log in.');
            Redirect::to('/login');
            return false;
        }
        return true;
    }
}
