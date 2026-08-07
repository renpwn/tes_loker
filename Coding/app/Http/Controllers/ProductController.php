<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
// use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        if ($request->ajax()) {
            $query = Product::with('category');

            return DataTables::of($query)
                ->addColumn('category', fn ($p) => $p->category?->name)
                // ->addColumn('price', fn ($p) => number_format($p->price, 2))
                ->addColumn('stock', fn ($p) => $p->current_stock)
                ->addColumn('actions', fn ($p) =>
                    view('products.actions', compact('p'))->render()
                )
                ->rawColumns(['actions'])

                // cara sorting untuk column computed
                ->orderColumn('stock', function ($query, $order) {
                // Sorting by initial_stock saja (approximation) atau bisa join subquery
                $query->orderBy('initial_stock', $order);
            })
                ->make(true);
        }

        return view('products.index');
    }

    public function create()
    {
        $this->authorize('create', Product::class);

        return view('products.create', [
            'categories' => Category::all(),
            'product' => new Product(), // 🔥 penting untuk form reuse
        ]);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id(); // assign user creator
        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->validated());
        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}
