<?php

namespace App\Controllers;

use App\Entities\Customer;
use App\Models\CustomerModel;
use App\Models\TransactionModel;
use CodeIgniter\HTTP\ResponseInterface;

class CustomerController extends BaseController
{
    protected CustomerModel $customerModel;
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->customerModel = model(CustomerModel::class);
        $this->transactionModel = model(TransactionModel::class);
    }

    /**
     * List all customers
     */
    public function index()
    {
        $data = [
            'customers' => $this->customerModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('customers/index', $data);
    }

    /**
     * Show create customer form
     */
    public function create()
    {
        return view('admin/customers/create');
    }

    /**
     * Store new customer
     */
    public function store(): ResponseInterface
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'phone' => 'required|min_length[10]|max_length[20]|is_unique[customers.phone]',
            'email' => 'permit_empty|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $customer = new Customer([
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email') ?: null,
            'points' => 0,
        ]);

        if ($this->customerModel->save($customer)) {
            return redirect()->to('/admin/customers')
                ->with('success', 'Customer created successfully.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create customer.');
    }

    /**
     * Show customer detail with transaction history
     */
    public function show(int $id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to('/admin/customers')
                ->with('error', 'Customer not found.');
        }

        $transactions = $this->transactionModel
            ->where('customer_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(20)
            ->findAll();

        $data = [
            'customer' => $customer,
            'transactions' => $transactions,
        ];

        return view('admin/customers/show', $data);
    }

    /**
     * Show edit customer form
     */
    public function edit(int $id)
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to('/admin/customers')
                ->with('error', 'Customer not found.');
        }

        $data = [
            'customer' => $customer,
        ];

        return view('admin/customers/edit', $data);
    }

    /**
     * Update customer
     */
    public function update(int $id): ResponseInterface
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to('/admin/customers')
                ->with('error', 'Customer not found.');
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'phone' => "required|min_length[10]|max_length[20]|is_unique[customers.phone,id,{$id}]",
            'email' => 'permit_empty|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $customer->fill([
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email') ?: null,
        ]);

        if ($this->customerModel->save($customer)) {
            return redirect()->to('/admin/customers')
                ->with('success', 'Customer updated successfully.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update customer.');
    }

    /**
     * Delete customer
     */
    public function delete(int $id): ResponseInterface
    {
        $customer = $this->customerModel->find($id);

        if (!$customer) {
            return redirect()->to('/admin/customers')
                ->with('error', 'Customer not found.');
        }

        // Check if customer has transactions
        $transactionCount = $this->transactionModel->where('customer_id', $id)->countAllResults();

        if ($transactionCount > 0) {
            return redirect()->to('/admin/customers')
                ->with('error', 'Cannot delete customer with transaction history.');
        }

        if ($this->customerModel->delete($id)) {
            return redirect()->to('/admin/customers')
                ->with('success', 'Customer deleted successfully.');
        }

        return redirect()->to('/admin/customers')
            ->with('error', 'Failed to delete customer.');
    }
}
