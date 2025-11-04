<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhereHas('category', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in database
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
            $image->move(public_path('images/products'), $imageName);
            $validated['image'] = 'images/products/' . $imageName;
        }

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);
        
        // Handle checkboxes
        $validated['is_featured'] = $request->has('is_featured') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Create product
        Product::create($validated);

        return redirect()->route('admin.products.index')
                       ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in database
     */
    public function update(Request $request, Product $product)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image deletion if requested
        if ($request->input('delete_image') == '1') {
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }
            $validated['image'] = 'images/products/default.jpg';
        }
        // Handle new image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && $product->image !== 'images/products/default.jpg' && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
            $image->move(public_path('images/products'), $imageName);
            $validated['image'] = 'images/products/' . $imageName;
        }

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);
        
        // Handle checkboxes
        $validated['is_featured'] = $request->has('is_featured') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Update the product
        $product->update($validated);

        return redirect()->route('admin.products.index')
                       ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from database
     */
    public function destroy(Product $product)
    {
        // Delete image file
        if ($product->image && $product->image !== 'images/products/default.jpg' && file_exists(public_path($product->image))) {
            @unlink(public_path($product->image));
        }

        // Delete product
        $product->delete();

        return redirect()->route('admin.products.index')
                       ->with('success', 'Product deleted successfully!');
    }

    /**
     * Delete product image via AJAX
     */
    public function deleteImage(Product $product)
    {
        try {
            // Check if product has an image
            if ($product->image && $product->image !== 'images/products/default.jpg') {
                // Delete physical file
                $imagePath = public_path($product->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                
                // Update database - set to default image
                $product->update(['image' => 'images/products/default.jpg']);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No image to delete'
            ], 404);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting product image: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image'
            ], 500);
        }
    }
}