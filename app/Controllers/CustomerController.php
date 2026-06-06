<?php

namespace App\Controllers;

use App\Helpers\View;
use App\Helpers\Redirect;
use App\Helpers\Session;
use App\Services\CustomerService;
use App\Services\UserService;

class CustomerController
{
    private CustomerService $customerService;
    private UserService $userService;

    public function __construct()
    {
        $this->customerService = new CustomerService();
        $this->userService = new UserService();
    }

    public function index(): void
    {
        $customers = $this->customerService->getAllCustomers();

        View::render('layouts.app', [
            'title' => 'Customers Management',
            'contentView' => 'customers.index',
            'customers' => $customers
        ]);
    }

    public function show(int $id): void
    {
        $customer = $this->customerService->getCustomerById($id);
        if (!$customer) {
            Session::flash('error', 'Customer not found.');
            Redirect::to('/customers');
        }

        View::render('layouts.app', [
            'title' => 'Customer Details: ' . e($customer['name']),
            'contentView' => 'customers.show',
            'customer' => $customer
        ]);
    }

    public function create(): void
    {
        $users = $this->userService->getAllUsers();
        View::render('layouts.app', [
            'title' => 'Create Customer',
            'contentView' => 'customers.create',
            'users' => $users
        ]);
    }

    public function store(): void
    {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'company_name' => trim($_POST['company_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'gst_number' => trim($_POST['gst_number'] ?? ''),
            'industry' => trim($_POST['industry'] ?? ''),
            'assigned_to' => $_POST['assigned_to'] ?: null
        ];

        if (empty($data['name'])) {
            Session::flash('error', 'Customer name is required.');
            Redirect::back();
        }

        $customerId = $this->customerService->createCustomer($data);

        if ($customerId) {
            Session::flash('success', 'Customer created successfully.');
            Redirect::to("/customers/{$customerId}");
        } else {
            Session::flash('error', 'Failed to create customer.');
            Redirect::back();
        }
    }

    public function edit(int $id): void
    {
        $customer = $this->customerService->getCustomerById($id);
        if (!$customer) {
            Session::flash('error', 'Customer not found.');
            Redirect::to('/customers');
        }

        $users = $this->userService->getAllUsers();
        View::render('layouts.app', [
            'title' => 'Edit Customer',
            'contentView' => 'customers.edit',
            'customer' => $customer,
            'users' => $users
        ]);
    }

    public function update(int $id): void
    {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'company_name' => trim($_POST['company_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'gst_number' => trim($_POST['gst_number'] ?? ''),
            'industry' => trim($_POST['industry'] ?? ''),
            'assigned_to' => $_POST['assigned_to'] ?: null
        ];

        if (empty($data['name'])) {
            Session::flash('error', 'Customer name is required.');
            Redirect::back();
        }

        if ($this->customerService->updateCustomer($id, $data)) {
            Session::flash('success', 'Customer updated successfully.');
            Redirect::to("/customers/{$id}");
        } else {
            Session::flash('error', 'Failed to update customer.');
            Redirect::back();
        }
    }

    public function storeContact(int $id): void
    {
        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'designation' => trim($_POST['designation'] ?? ''),
            'is_primary' => isset($_POST['is_primary']) ? 1 : 0
        ];

        if (empty($data['first_name']) || empty($data['last_name'])) {
            Session::flash('error', 'Contact first and last name are required.');
            Redirect::back();
        }

        if ($this->customerService->addContact($id, $data)) {
            Session::flash('success', 'Contact added successfully.');
        } else {
            Session::flash('error', 'Failed to add contact.');
        }

        Redirect::back();
    }
}
