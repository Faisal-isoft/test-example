<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductService
{
    public function getAll()
    {
        return Product::all();
    }

    public function create(Request $request): Product
    {
        $product = Product::create($request->only(['name', 'description', 'price']));
        $product->image = $this->handleImageUpload($request);
        $product->save();

        return $product;
    }

    public function update(Product $product, Request $request): void
    {
        $oldPrice = $product->price;

        $product->fill($request->only(['name', 'description', 'price']));
        if ($request->hasFile('image')) {
            $product->image = $this->handleImageUpload($request);
        }

        $product->save();

        if ($oldPrice != $product->price) {
            try {
                SendPriceChangeNotification::dispatch(
                    $product,
                    $oldPrice,
                    $product->price,
                    env('PRICE_NOTIFICATION_EMAIL', 'admin@example.com')
                );
            } catch (\Exception $e) {
                Log::error('Notification dispatch failed: ' . $e->getMessage());
            }
        }
    }

    public function delete(Product $product): void
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
    }

    private function handleImageUpload(Request $request): ?string
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $filename, 'public');
            return 'storage/products/' . $filename;
        }

        return 'product-placeholder.jpg';
    }
}
