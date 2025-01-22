<?php

namespace App\Http\Controllers\Admin\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Jobs\SendLowStockNotification;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Cache::remember('products', 60, function () {
                return Product::all();
            });

            // Return a successful response
            $message = $products->isEmpty()
                ? 'No products found in the database.'
                : 'Products retrieved successfully.';
            return ApiResponse::success($message, $products);
        } catch (Exception $e) {
            return ApiResponse::error('Failed to retrieve products.', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock_quantity' => 'required|integer|min:0',
                'min_notification_stock' => 'required|integer|min:0',
            ]);

            $product = Product::create($validated);

            return ApiResponse::success('New product created successfully.', $product, 201);
        } catch (Exception $e) {
            return ApiResponse::error('Validation failed.', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Cache::remember("product_{$id}", 60, function () use ($id) {
                return Product::findOrFail($id);
            });

            return ApiResponse::success('Product retrieved successfully.', $product);
        } catch (Exception $e) {
            return ApiResponse::error('Failed to retrieve product.', $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());

            if ($product->stock_quantity < $product->min_notification_stock) {
                $trigger = dispatch(new SendLowStockNotification($product));
                info('Mail trigger', [$trigger]);
            }

            Cache::forget('products');
            Cache::forget("product_{$id}");

            return ApiResponse::success('Product updated successfully.', $product);
        } catch (Exception $e) {
            return ApiResponse::error('Failed to update product.', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find and delete the product
            $product = Product::findOrFail($id);
            $product->delete();

            // Clear the related cache
            Cache::forget('products');
            Cache::forget("product_{$id}");

            // Return a success response
            return ApiResponse::success('Product deleted successfully.', null, 200);
        } catch (Exception $e) {
            // Handle exceptions and return an error response
            return ApiResponse::error('Failed to delete the product.', $e->getMessage());
        }
    }
}
