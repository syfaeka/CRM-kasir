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
            'image' => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $productData = [
            'sku' => $this->request->getPost('sku'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
        ];

        // Handle image upload
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/products', $newName);
            $productData['image'] = $newName;
        }

        $product = new Product($productData);

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
            'image' => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $productData = [
            'sku' => $this->request->getPost('sku'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
        ];

        // Handle image upload
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old image if exists
            if (!empty($product->image)) {
                $oldImagePath = FCPATH . 'uploads/products/' . $product->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/products', $newName);
            $productData['image'] = $newName;
        }

        $product->fill($productData);

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

        // Delete product image if exists
        if (!empty($product->image)) {
            $imagePath = FCPATH . 'uploads/products/' . $product->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($this->productModel->delete($id)) {
            return redirect()->to('/admin/products')
                ->with('success', 'Product deleted successfully.');
        }

        return redirect()->to('/admin/products')
            ->with('error', 'Failed to delete product.');
    }
}
