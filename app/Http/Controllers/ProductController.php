<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use PhpParser\Node\Stmt\TryCatch;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::all();
        $products = Product::all();
        return view('pages.product', compact('categories', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {

        // store image and get url path
        $image = $request->file('thumbnail');
        $image_name = time() . '.' . $image->extension();
        $image->storeAs('public/images/products', $image_name);

        Product::create([
            "name" => $request["name"],
            "description" => $request["description"],
            "category_id" => $request["category"],
            "image" => $image_name,
            "isSoldOut" => false,
            "imageUrl" => URL::asset('storage/images/products') . '/' . $image_name
        ]);

        return redirect('/product')->with('toast_success', 'Product Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::getProduct($id);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {

        try {
            $product = Product::findOrFail($request['id']);
            // delete old image and insert new image
            if ($request->hasFile('thumbnail')) {
                Storage::delete('public/images/products/' . $product->image);
                $image = $request->file('thumbnail');
                $image_name = time() . '.' . $image->extension();
                $image->storeAs('public/images/products', $image_name);
                $product->image = $image_name;
                $product->imageUrl = URL::asset('storage/images/products') . '/' . $image_name;
            } else {
                $product->image = $product->image;
                $product->imageUrl = $product->imageUrl;
            }
            // parse isSoldOut
            if ($request['isSold'] == "1") {
                $soldOut = 1;
            } else {
                $soldOut = 0;
            }
            Product::where('id', $request['id'])->update([
                "name" => $request["name"],
                "description" => $request["description"],
                "category_id" => $request["category"],
                "isSoldOut" => $soldOut,
                "imageUrl" => $product->imageUrl,
                "image" => $product->image
            ]);
            return redirect('/product')->with('toast_success', 'Product Updated Successfully');
        } catch (\Throwable $th) {
            return redirect('/product')->with('toast_error', 'Invalid Data');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $product = Product::findOrFail($request['id']);
        Storage::delete('public/images/products/' . $product->image);
        $product->delete();
        return redirect('/product')->with('toast_success', 'Product Deleted Successfully');
    }

    public function tests(){
        // get products along with its variant
        $products = Product::with('variants')->get();
        return response()->json($products);
    }

    public function orders(Request $request){
        // dd($request->all());
        // return status
        return response()->json([
            "status" => "success"
        ]);
    }
}
