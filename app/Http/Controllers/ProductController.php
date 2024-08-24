<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\ProductAssets;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::with('category')->orderBy('price', 'DESC')->get();
        $categories = Categories::all();
        return view('pages.product', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->route('product')->withErrors($validator)->withInput();
        }

        $product = new Products();
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->save();

        $files = $request->file('images');

        foreach ($files as $file) {
            $filename = 'images_' . uniqid() . $file->getClientOriginalExtension();

            if (!File::exists(storage_path('app/public/img/product_images'))) {
                File::makeDirectory(storage_path('app/public/img/product_images'), 0777, true, true);
            }

            $file->storeAs('public/img', $filename);

            $product_asset = new ProductAssets();
            $product_asset->product_id = $product->id;
            $product_asset->image = $filename;
            $product_asset->save();
        }

        return redirect()->route('product')->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Products $product, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->route('product')->withErrors($validator)->withInput();
        }

        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->save();

        if ($request->hasFile('images')) {
            $existingImages = ProductAssets::where('product_id', $product->id)->get();

            foreach ($existingImages as $image) {
                $oldImagePath = storage_path('app/public/img/product_images/' . $image->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $image->delete();
            }

            $files = $request->file('images');

            foreach ($files as $file) {
                $filename = 'images_' . uniqid() . '.' . $file->getClientOriginalExtension();

                if (!File::exists(storage_path('app/public/img/product_images'))) {
                    File::makeDirectory(storage_path('app/public/img/product_images'), 0777, true, true);
                }

                $file->storeAs('public/img/product_images', $filename);

                $product_asset = new ProductAssets();
                $product_asset->product_id = $product->id;
                $product_asset->image = $filename;
                $product_asset->save();
            }
        }

        return redirect()->route('product')->with('success', 'Data berhasil diubah!');
    }

    public function destroy(Products $product)
    {
        $productAssets = ProductAssets::where('product_id', $product->id)->get();

        foreach ($productAssets as $asset) {
            $imagePath = storage_path('app/public/img/product_images/' . $asset->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('product')->with('success', 'Produk berhasil dihapus!');
    }
}
