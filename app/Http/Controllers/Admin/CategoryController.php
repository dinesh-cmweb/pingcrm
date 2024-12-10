<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Category;
use App\Helper\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Category/Index', [
            'categories' => Category::query()->paginate(10),
        ]);
    }

    public function save(CategoryRequest $request)
    {
        $inputs = $request->validated();
        if($request->file('image')){
            $inputs['image'] = CommonHelper::uploadFile($request->file('image'), 'category');
        }

        Category::query()->create($inputs);

        return redirect()->back()->with('success', 'Category created successfully');
    }
    
    public function edit(Category $category)
    {
        return response()->json($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $inputs = $request->validated();
        if($request->file('image')){
            $inputs['image'] = CommonHelper::uploadFile($request->file('image'), 'category', $category->image);
        }
        $category->update($inputs);

        return redirect()->back()->with('success', 'Category updated successfully');
    }

    public function delete(Category $category)
    {
        try {
            DB::beginTransaction();
            $category->delete();
            DB::commit();

            return redirect()->back()->with('success','Category deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            if($e->getCode() == 23000){
                return redirect()->back()->with('error', "Category is associated with other record. Cannot be deleted");
            }
            Log::error('Category deleted Exception: ' . $e->getMessage());
            return redirect()->back()->with('error', 'something went wrong');
        }
    }
}
