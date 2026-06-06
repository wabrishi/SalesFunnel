<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Helpers\Redirect;
use App\Helpers\Session;
use App\Services\UserService;
use App\Services\RoleService;

class UserController
{
    private UserService $userService;
    private RoleService $roleService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->roleService = new RoleService();
    }

    public function index(): void
    {
        View::render('layouts.app', [
            'title' => 'Manage Users',
            'contentView' => 'users.index',
            'users' => $this->userService->getAllUsers()
        ]);
    }

    public function create(): void
    {
        View::render('layouts.app', [
            'title' => 'Create User',
            'contentView' => 'users.create',
            'roles' => $this->roleService->getAllRoles()
        ]);
    }

    public function store(): void
    {
        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'status' => $_POST['status'] ?? 'active'
        ];

        if (empty($data['email']) || empty($data['password'])) {
            Session::flash('error', 'Email and password required.'); Redirect::back();
        }

        if ($this->userService->createUserWithRoles($data, $_POST['roles'] ?? [])) {
            Session::flash('success', 'User created.'); Redirect::to('/users');
        } else {
            Session::flash('error', 'Creation failed.'); Redirect::back();
        }
    }
}
