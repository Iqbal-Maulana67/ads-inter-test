<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Categories::all();

        return view('pages.category', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
        {
            return redirect()->route('category')->withErrors($validator)->withInput();
        }

        $category = new Categories();
        $category->name = $request->name;
        $category->save();

        return redirect()->route('category')->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Categories $category, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
        {
            return redirect()->route('category')->withErrors($validator)->withInput();
        }

        $category->name = $request->name;
        $category->save();

        return redirect()->route('category')->with('success', 'Data berhasil diubah');
    }
    
    public function destroy(Categories $category)
    {
        $category->delete();

        return redirect()->route('category')->with('success', 'Data berhasil dihapus');
    }
}
