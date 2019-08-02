<?php

namespace App\Http\Controllers;

use App\Cts;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        //only index and show can be accessed by guest users
        $this->middleware('auth')->except(['index', 'show']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product  = Product::create($request->all());

        //if product is a bundle then sync the records for the relationships and attach then to the result
        if($request->type == Cts::BUNDLE_PRODUCT_TYPE)
        {
            $product->bundle()->sync( json_decode($request->bundledItems, false));
            $product->bundledItems = $product->bundle;
        }

        return  $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        if($product->type == Cts::BUNDLE_PRODUCT_TYPE) //check if product is a bundle to save processing added queries
        {
            $product->bundledItems = $product->bundle;
        }

        return $product;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //get ids of of products that shuold be bundled with this product
        $product = Product::findOrFail($id);

        //update except for bundled items data
        $product->update($request->except('bundledItems'));

        //update bundled items - if product is a bundle then sync the records for the relationships and attach then to the result
        if($product->type == Cts::BUNDLE_PRODUCT_TYPE)
        {
            $product->bundle()->sync( json_decode($request->bundledItems, false));
            $product->bundledItems = $product->bundle;
            unset($product->bundle); //maintain consistency of variable naming from 'bundle' to 'bundledItems'
        }

        return  $product;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
    }
}
