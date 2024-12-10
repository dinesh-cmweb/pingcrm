<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Helper\CommonHelper;
use App\Models\ProductMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Product/Index', [
            'products' => Product::query()->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Product/Create', [
            'categories' => Category::query()->get()->pluck('name', 'id'),
        ]);
    }

    public function save(ProductRequest $request)
    {
        $inputs = $request->validated();
        $images = $inputs['images'];
        unset($inputs['images']);
        $product = Product::query()->create($inputs);

        $productMedia = [];
        if ($images) {
            foreach ($images as $image) {
                $url = CommonHelper::uploadFile($image, 'product');
                $productMedia[] = [
                    'product_id' => $product->id,
                    'url' => $url,
                    'created_at' => now(),
                ];
            }
        }

        ProductMedia::query()->insert($productMedia);

        return redirect()->route('admin.products')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $product = $product->load('medias');
        $product['images'] = $product['medias']->pluck('url', 'id');
        unset($product['medias']);
        $product['images'] = $product['images']->toArray();

        return Inertia::render('Admin/Product/Create', [
            'product' => $product,
            'categories' => Category::query()->get()->pluck('name', 'id'),
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $inputs = $request->validated();
        $images = $inputs['images'];
        unset($inputs['images']);

        $product->update($inputs);

        $productMedia = [];
        if ($images) {
            foreach ($images as $image) {
                $url = CommonHelper::uploadFile($image, 'product');
                $productMedia[] = [
                    'product_id' => $product->id,
                    'url' => $url,
                    'created_at' => now(),
                ];
            }
        }
        ProductMedia::query()->insert($productMedia);

        return redirect()->route('admin.products')->with('success', 'Product created successfully');
    }

    public function delete(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    public function deleteImage($productId, ProductMedia $imageId)
    {
        CommonHelper::removeOldFile("public/product/".$imageId->url);
        $imageId->delete();
        return redirect()->back()->with('success', 'Image deleted successfully');
    }
}
