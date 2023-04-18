<?php

namespace App\Http\Controllers;

use App\Models\Variant;
use App\Http\Requests\StoreVariantRequest;
use App\Http\Requests\UpdateVariantRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get variant with product
        $variants = Variant::with('product')->get()->sortBy('product.name');
        $products = Product::get();
        return view('pages.variant', compact('products', 'variants'));
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
     * @param  \App\Http\Requests\StoreVariantRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVariantRequest $request)
    {
        //
        Variant::create([
            "variant_name" => $request["name"],
            "price" => $request["price"],
            "product_id" => $request["product_id"],
        ]);

        return redirect('/variant')->with('toast_success', 'Variant Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $variant = Variant::getVariant($id);
        return response()->json($variant);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function edit(Variant $variant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVariantRequest  $request
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVariantRequest $request, $id)
    {
        //
        Variant::where('id', $request["id"])->update([
            "variant_name" => $request["name"],
            "price" => $request["price"],
            "product_id" => $request["product_id"],
        ]);

        return redirect('/variant')->with('toast_success', 'Variant Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        Variant::where('id', $request["id"])->delete();
        return redirect('/variant')->with('toast_success', 'Variant Deleted Successfully');
    }
}
