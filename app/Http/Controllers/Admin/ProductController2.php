<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductItemRequest;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Helper\CommonHelper;
use App\Models\ProductMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest2;

class ProductController2 extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Product2/Index', [
            'products' => Product::query()->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Product2/Create', [
            'categories' => Category::query()->get()->pluck('name', 'id'),
        ]);
    }

    public function save(ProductRequest2 $request)
    {
        $inputs = $request->validated();
        $product = Product::query()->create($inputs);

        return redirect()->route('admin.product2.edit', $product->id)->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $product = $product->load('medias');

        return Inertia::render('Admin/Product2/Create', [
            'product' => $product,
            'categories' => Category::query()->get()->pluck('name', 'id'),
        ]);
    }

    public function update(ProductRequest2 $request, Product $product)
    {
        $inputs = $request->validated();
        $product->update($inputs);

        return redirect()->route('admin.product2.edit', $product->id)->with('success', 'Product created successfully');
    }

    public function delete(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    public function saveItem(Product $product, ProductItemRequest $request)
    {
        $inputs = $request->validated();

        if($request->file('image')){
            $inputs['url'] = CommonHelper::uploadFile($request->file('image'), 'product');
        }
        unset($inputs['image']);
        $inputs['product_id'] = $product->id;
        ProductMedia::query()->create($inputs);

        return redirect()->back()->with('success', 'Product media created successfully');
    }

    public function editItem($product, ProductMedia $productMedia)
    {
        return response()->json($productMedia);
    }

    public function updateItem(Product $product, ProductMedia $productMedia,  ProductItemRequest $request)
    {
        $inputs = $request->validated();
        if($request->file('image')){
            $inputs['url'] = CommonHelper::uploadFile($request->file('image'), 'product', $productMedia->url);
        }
        unset($inputs['image']);
        $productMedia->update($inputs);

        return redirect()->back()->with('success', 'Product media updated successfully');
    }

    public function deleteItem(Product $product, ProductMedia $productMedia)
    {
        CommonHelper::removeOldFile("public/product/".$productMedia->url);
        $productMedia->delete();
        return redirect()->back()->with('success', 'Product media Deleted successfully');
    }
}
