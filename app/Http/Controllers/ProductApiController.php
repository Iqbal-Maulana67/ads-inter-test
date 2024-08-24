<?php

namespace App\Http\Controllers;

use App\Models\ProductAssets;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::with('category')->get();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if($validator->fails())
        {
            return response()->json(['message' => 'Penambahan data gagal!']);
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
        return response()->json(['message' => 'Data berhasil ditambahkan', 'product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'price' => 'nullable|integer',
            'category_id' => 'nullable|exists:categories,id',
            'images.*' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if($validator->fails())
        {
            return response()->json(['message' => 'Pengubahan data gagal!']);
        }

        if($request->has('name'))
        {
            $product->name = $request->name;
        }

        if($request->has('slug'))
        {
            $product->slug = $request->slug;
        }

        if($request->has('price'))
        {
            $product->price = $request->price;
        }

        if($request->has('category_id'))
        {
            $product->category_id = $request->category_id;
        }

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

        return response()->json(['message' => 'Data berhasil diubah', 'product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Products $product)
    {
        $product->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
