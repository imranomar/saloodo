<?php

namespace App\Http\Controllers;

use \App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order  = Order::create($request->all());

        //if sync products in this order
        if($request->products)
        {
            //e.g. needed product ids with their quantities [1=>['quantity'=>3],2=>['quantity'=>22]]
            //e.g. needed product ids with their quantities print_r(json_decode('{ "1": { "quantity": "22" },"6":
            // { "quantity": "33" }}', true));
            $arr_prods_and_quantities = json_decode($request->products,true);
            $order->products()->sync( $arr_prods_and_quantities);
            $order->products_list = $order->products;
        }

        return  $order;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Order::with('products')->findOrFail($id);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
