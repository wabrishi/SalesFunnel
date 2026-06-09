<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Helpers\Session;

class CustomerService
{
    private CustomerRepository $customerRepository;
    private AuditLogService $auditLogService;

    public function __construct()
    {
        $this->customerRepository = new CustomerRepository();
        $this->auditLogService = new AuditLogService();
    }

    public function getAllCustomers(): array
    {
        return $this->customerRepository->getAll();
    }

    public function getCustomerById(int $id): ?array
    {
        $customer = $this->customerRepository->findById($id);
        if ($customer) {
            $customer['contacts'] = $this->customerRepository->getContacts($id);
            $customer['opportunities'] = $this->customerRepository->getOpportunities($id);
        }
        return $customer;
    }

    public function createCustomer(array $data): ?int
    {
        $data['created_by'] = Session::get('user_id');
        $customerId = $this->customerRepository->create($data);

        if ($customerId) {
            $this->auditLogService->log('Created Customer', 'Customer', $customerId, null, $data);
            return $customerId;
        }

        return null;
    }

    public function updateCustomer(int $id, array $data): bool
    {
        $oldCustomer = $this->customerRepository->findById($id);
        if (!$oldCustomer) return false;

        $success = $this->customerRepository->update($id, $data);

        if ($success) {
            $this->auditLogService->log('Updated Customer', 'Customer', $id, $oldCustomer, $data);
        }

        return $success;
    }

    public function addContact(int $customerId, array $data): bool
    {
        $data['customer_id'] = $customerId;
        if ($this->customerRepository->addContact($data)) {
            $this->auditLogService->log('Added Contact to Customer', 'Customer', $customerId, null, $data);
            return true;
        }
        return false;
    }
}
