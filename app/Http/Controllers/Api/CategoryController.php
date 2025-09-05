<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // List categories
    public function index()
    {
        return response()->json([
            'data' => Category::all()
        ]);
    }


    public function store(Request $request)
{
    if (!$request->user() || !$request->user()->isAdmin()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name',
    ]);

    $category = Category::create([
        'name' => $request->name,
    ]);

    return response()->json([
        'message' => '✅ Category created successfully',
        'data' => $category,
    ], 201);
}

    // Store new category
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255|unique:categories,name',
    //     ]);

    //     $category = Category::create([
    //         'name' => $request->name,
    //     ]);

    //     return response()->json([
    //         'message' => '✅ Category created successfully',
    //         'data' => $category,
    //     ], 201);
    // }
}
