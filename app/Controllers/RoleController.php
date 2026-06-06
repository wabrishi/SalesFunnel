<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Helpers\Redirect;
use App\Helpers\Session;
use App\Services\RoleService;

class RoleController
{
    private RoleService $roleService;

    public function __construct()
    {
        $this->roleService = new RoleService();
    }

    public function index(): void
    {
        $roles = $this->roleService->getAllRoles();
        View::render('layouts.app', [
            'title' => 'Manage Roles',
            'contentView' => 'roles.index',
            'roles' => $roles
        ]);
    }

    public function create(): void
    {
        $permissions = $this->roleService->getAllPermissions();
        View::render('layouts.app', [
            'title' => 'Create Role',
            'contentView' => 'roles.create',
            'permissions' => $permissions
        ]);
    }

    public function store(): void
    {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];
        $permissionIds = $_POST['permissions'] ?? [];

        if (empty($data['name'])) {
            Session::flash('error', 'Role name is required.');
            Redirect::back();
        }

        if ($this->roleService->createRole($data, $permissionIds)) {
            Session::flash('success', 'Role created successfully.');
            Redirect::to('/roles');
        } else {
            Session::flash('error', 'Failed to create role. It may already exist.');
            Redirect::back();
        }
    }

    public function edit(int $id): void
    {
        $role = $this->roleService->getRoleById($id);
        if (!$role) {
            Session::flash('error', 'Role not found.');
            Redirect::to('/roles');
        }

        $permissions = $this->roleService->getAllPermissions();
        $rolePermissions = $this->roleService->getRolePermissions($id);

        View::render('layouts.app', [
            'title' => 'Edit Role',
            'contentView' => 'roles.edit',
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function update(int $id): void
    {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];
        $permissionIds = $_POST['permissions'] ?? [];

        if (empty($data['name'])) {
            Session::flash('error', 'Role name is required.');
            Redirect::back();
        }

        if ($this->roleService->updateRole($id, $data, $permissionIds)) {
            Session::flash('success', 'Role updated successfully.');
            Redirect::to('/roles');
        } else {
            Session::flash('error', 'Failed to update role.');
            Redirect::back();
        }
    }
}
