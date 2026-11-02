<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductApproval;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['vendor', 'category', 'images'])->latest();

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->input('approval_status'));
        } else {
            $query->where('approval_status', 'pending');
        }

        if ($request->filled('search')) {
            $term = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $request->input('search')) . '%';
            $query->where(fn ($q) => $q->where('name', 'ilike', $term)
                ->orWhere('sku', 'ilike', $term)
                ->orWhereHas('vendor', fn ($v) => $v->where('business_name', 'ilike', $term)));
        }

        $products = $query->paginate(20)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product): View
    {
        $product->load(['vendor', 'category', 'brand', 'images']);
        $categories = Category::where('status', true)->orderBy('name')->get();
        return view('admin.products.show', compact('product', 'categories'));
    }

    public function approve(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        $category = Category::whereKey($data['category_id'])->where('status', true)->firstOrFail();

        DB::transaction(function () use ($request, $product, $data, $category) {
            $product->update([
                'category_id' => $category->id,
                'approval_status' => 'approved',
                'publication_status' => 'published',
                'approved_at' => now(),
            ]);

            ProductApproval::create([
                'product_id' => $product->id,
                'reviewed_by' => $request->user()->id,
                'status' => 'approved',
                'remarks' => $data['remarks'] ?? null,
                'reviewed_at' => now(),
            ]);
        });

        return redirect()->route('admin.products.index')->with('success', 'Product approved and assigned to the global category.');
    }

    public function reject(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate(['remarks' => ['required', 'string', 'max:2000']]);

        DB::transaction(function () use ($request, $product, $data) {
            $product->update([
                'approval_status' => 'rejected',
                'publication_status' => 'draft',
            ]);

            ProductApproval::create([
                'product_id' => $product->id,
                'reviewed_by' => $request->user()->id,
                'status' => 'rejected',
                'remarks' => $data['remarks'],
                'reviewed_at' => now(),
            ]);
        });

        return redirect()->route('admin.products.index')->with('success', 'Product rejected.');
    }
}
