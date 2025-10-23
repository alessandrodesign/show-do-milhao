<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $data         = $request->validate(['name' => 'required|string|unique:categories,name']);
        $data['slug'] = Str::slug($data['name']);

        return Category::create($data);
    }

    public function show(Category $category)
    {
        return $category;
    }

    public function update(Request $request, Category $category)
    {
        $data         = $request->validate(['name' => 'required|string|unique:categories,name,' . $category->id]);
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);

        return $category;
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->noContent();
    }
}
