<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::with('orderItems');

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('price', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(config('erp.pagination_size'));

        $totalProducts = Product::count();
        $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')->count();
        $outOfStockCount = Product::where('stock_quantity', 0)->count();

        return view('products.index', compact(
            'products', 'totalProducts', 'lowStockCount', 'outOfStockCount'
        ));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                if ($path === false) {
                    throw new \RuntimeException('File upload failed.');
                }
                $validated['image'] = $path;
            }

            Product::create($validated);
        } catch (\Exception $e) {
            Log::error('Product creation failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $path = $request->file('image')->store('products', 'public');
                if ($path === false) {
                    throw new \RuntimeException('File upload failed.');
                }
                $validated['image'] = $path;
            }

            $product->update($validated);
        } catch (\Exception $e) {
            Log::error('Product update failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();
        } catch (\Exception $e) {
            Log::error('Product deletion failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
