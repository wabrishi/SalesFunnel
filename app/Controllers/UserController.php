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

    public function edit(int $id): void
    {
        $user = (new \App\Repositories\UserRepository())->findById($id);

        if (!$user) {
            Session::flash('error', 'User not found.');
            Redirect::to('/users');
        }

        $allRoles = clone (object)$this->roleService;
        $allRoles = $this->roleService->getAllRoles();

        $db = \App\Services\Database::getInstance();
        $stmt = $db->prepare("SELECT role_id FROM user_roles WHERE user_id = ?");
        $stmt->execute([$id]);
        $assignedRoleIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        View::render('layouts.app', [
            'title' => 'Edit User',
            'contentView' => 'users.edit',
            'user' => $user,
            'roles' => $allRoles,
            'assignedRoleIds' => $assignedRoleIds
        ]);
    }

    public function update(int $id): void
    {
        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'status' => $_POST['status'] ?? 'active'
        ];

        if (empty($data['email']) || empty($data['first_name'])) {
            Session::flash('error', 'First Name and Email are required.'); Redirect::back();
        }

        if ($this->userService->updateUserWithRoles($id, $data, $_POST['roles'] ?? [])) {
            Session::flash('success', 'User updated.'); Redirect::to('/users');
        } else {
            Session::flash('error', 'Update failed.'); Redirect::back();
        }
    }
}
