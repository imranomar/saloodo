<?php

namespace App\Http\Controllers;

use App\Cts;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        //user should be registered and have a role of admin to do all except viewing product details
        $this->middleware(['auth:api','admin'])->except(['index', 'show']);
    }


    /**
     * Products - list all.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::with('bundle')->paginate(Cts::ITEMS_PER_PAGE_PAGING);
    }

    /**
     * Product - create.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product  = Product::create($request->all());

        //if product is a bundle then sync the records for the relationships and attach then to the result
        //if($request->type == Cts::BUNDLE_PRODUCT_TYPE)
        //{
        //e.g. json expected ids of products that should be bundled e.g [1,3]
        $product->bundle()->sync( json_decode($request->bundle, false));
        $product->load('bundle');
        //}

        return  $product;
    }

    /**
     * Product - get a product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        //if($product->type == Cts::BUNDLE_PRODUCT_TYPE) //check if product is a bundle to save processing added queries
        //{
            $product->load('bundle');
        //}

        return $product;
    }



    /**
     * Product - update
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        //get ids of of products that should be bundled with this product
        $product = Product::findOrFail($id);

        //update except for bundled items data
        $product->update($request->except('bundle'));

        //update bundled items - if product is a bundle then sync the records for the relationships and attach then to the result
        //if($product->type == Cts::BUNDLE_PRODUCT_TYPE)
        //{
            //e.g. json expected ids of products that should be bundled e.g [1,3]
            $product->bundle()->sync( json_decode($request->bundle, false));
            $product->load('bundle');
        //}

        return  $product;

    }

    /**
     * Product - delete
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->destroy($id);
    }
}
