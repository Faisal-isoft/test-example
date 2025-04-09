<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;

class AdminProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    /**
     * Display a list of all products in admin panel.
     */
    public function products()
    {
        $products = $this->productService->getAll();
        return view('admin.list', compact('products'));
    }

    /**
     * Show the form to add a new product.
     */
    public function addProductForm()
    {
        return view('admin.add_product');
    }

    /**
     * Store a newly created product.
     */
    public function addProduct(StoreProductRequest $request)
    {
        $this->productService->create($request);
        return redirect()->route('admin.products')->with('success', 'Product added successfully');
    }

    /**
     * Show the form to edit an existing product.
     */
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.edit_product', compact('product'));
    }

    /**
     * Update an existing product.
     */
    public function updateProduct(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $this->productService->update($product, $request);
        return redirect()->route('admin.products')->with('success', 'Product updated successfully');
    }

    /**
     * Delete a product.
     */
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $this->productService->delete($product);
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully');
    }
}
