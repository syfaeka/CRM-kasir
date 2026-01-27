<?php

namespace App\Controllers;

use App\Entities\Product;
use App\Models\ProductModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProductController extends BaseController
{
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = model(ProductModel::class);
    }

    /**
     * List all products
     */
    public function index()
    {
        $data = [
            'products' => $this->productModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('products/index', $data);
    }

    /**
     * Show create product form
     */
    public function create()
    {
        return view('admin/products/create');
    }

    /**
     * Store new product
     */
    public function store(): ResponseInterface
    {
        $rules = [
            'sku' => 'required|min_length[2]|max_length[50]|is_unique[products.sku]',
            'name' => 'required|min_length[2]|max_length[255]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock' => 'required|integer|greater_than_equal_to[0]',
            'description' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $product = new Product([
            'sku' => $this->request->getPost('sku'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
        ]);

        if ($this->productModel->save($product)) {
            return redirect()->to('/admin/products')
                ->with('success', 'Product created successfully.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create product.');
    }

    /**
     * Show edit product form
     */
    public function edit(int $id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')
                ->with('error', 'Product not found.');
        }

        $data = [
            'product' => $product,
        ];

        return view('admin/products/edit', $data);
    }

    /**
     * Update product
     */
    public function update(int $id): ResponseInterface
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')
                ->with('error', 'Product not found.');
        }

        $rules = [
            'sku' => "required|min_length[2]|max_length[50]|is_unique[products.sku,id,{$id}]",
            'name' => 'required|min_length[2]|max_length[255]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'stock' => 'required|integer|greater_than_equal_to[0]',
            'description' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $product->fill([
            'sku' => $this->request->getPost('sku'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
        ]);

        if ($this->productModel->save($product)) {
            return redirect()->to('/admin/products')
                ->with('success', 'Product updated successfully.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update product.');
    }

    /**
     * Delete product
     */
    public function delete(int $id): ResponseInterface
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')
                ->with('error', 'Product not found.');
        }

        if ($this->productModel->delete($id)) {
            return redirect()->to('/admin/products')
                ->with('success', 'Product deleted successfully.');
        }

        return redirect()->to('/admin/products')
            ->with('error', 'Failed to delete product.');
    }
}
