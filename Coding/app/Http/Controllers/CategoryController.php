<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // $categories = Category::paginate(10);
        // return view('categories.index', compact('categories'));

        $this->authorize('viewAny', Category::class);

        if ($request->ajax()) {
            return DataTables::of(Category::query())
                // ->addColumn('is_service', fn ($cat) => $cat->is_service ? 'Yes' : 'No')
                ->addColumn('actions', function ($cat) {
                    return view('categories.actions', compact('cat'))->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('categories.index');
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        $data['is_service'] = $data['is_service'] ?? 0;
        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {        
        $this->authorize('update', $category);

        $validated = $request->validate([
            'code'       => 'required|string|max:50|unique:categories,code,' . $category->id,
            'name'       => 'required|string|max:255',
            'is_service' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {        
        $this->authorize('delete', $category);

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}
