<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::when(request('search'), function($query) {
            $query->where('name', 'like', '%'.request('search').'%')
                ->orWhere('price', 'like', '%'.request('search').'%');
        })->orderBy('id', 'desc')->paginate(5);

        return $products;
    }

    public function store(ProductStoreRequest $request, Product $product)
    {
        $product = $product->create($request->all());
        return $product;
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update($request->all());
        return 'success';
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return $product;
    }
}
