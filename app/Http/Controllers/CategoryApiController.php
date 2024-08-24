<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categories::all();

        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
        {
            return response()->json(['message' => 'Penambahan data gagal!']);
        }

        $category = new Categories();
        $category->name = $request->name;
        $category->save();

        return response()->json(['message' => 'Data berhasil ditambahkan', 'category' => $category]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categories $categories)
    {
        return response()->json($categories);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categories $categories)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
        {
            return response()->json(['message' => 'Penambahan data gagal!']);
        }

        $categories->name = $request->name;
        $categories->save();

        return response()->json(['message' => 'Data berhasil diubah', 'category' => $categories]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categories $categories)
    {
        $categories->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
