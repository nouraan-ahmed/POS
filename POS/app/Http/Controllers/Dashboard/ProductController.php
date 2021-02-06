<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
   
    public function index(Request $request)
    {
       
        
        $categories=Category::all();

        $products = Product::when($request->search, function ($query) use ($request) {
            return $query->where('name','like', '%' . $request->search. '%');
        })->when($request->category_id, function ($q) use ($request){
            return $q->where('category_id', $request->category_id);
        })->latest()->paginate(8);


        
        return view('dashboard.products.index', compact('products','categories'));
    }

    
    public function create()
    {
        $categories = Category::all();
        return view('dashboard.products.create', compact('categories'));
    }

    
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|unique:products,name',
            'description' => 'required|unique:products,description',
            'category_id' => 'required',
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'stock' => 'required',
        ]);

        $request_data = $request->all();

        if ($request->image) {

            //save the images in folder

            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/product_images/' . $request->image->hashName()));

            //add image to the request

            $request_data['image'] = $request->image->hashName();
        }
        //dd($request_data);
        Product::create($request_data);
        session()->flash('success', __('site.added_successfully'));
        return Redirect('dashboard/products/index');
    }

    
    public function edit(Product $product)
    {
        $categories=Category::all();
        return view('dashboard.products.edit',compact('categories','product'));
    }

    
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'stock' => 'required',
        ]);

        $request_data = $request->all();

        if ($request->image) {

            if($product->image!='default.png'){
                Storage::disk('public_uploads')->delete('/product_images/'.$product->image);
            }

            //save the images in folder

            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/product_images/' . $request->image->hashName()));

            //add image to the request

            $request_data['image'] = $request->image->hashName();

            $product->update($request_data);
            session()->flash('success',__('site.updated_successfully'));
            return redirect('dashboard/products/index');

        }
    }

    public function destroy(Product $product)
    {

        if($product->image!='default.png'){
            Storage::disk('public_uploads')->delete('/product_images/'.$product->image);
        }

        $product->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect('dashboard/products/index');

    }
}