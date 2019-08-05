<?php

namespace App\Http\Controllers;

use App\Cts;
use \App\Order;
use Exception;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{

    public function __construct()
    {
        //need to signed in to access all
        $this->middleware(['auth:api']);

        //admin can  : update,destroy,index
        $this->middleware(['admin'])->except(['store', 'show',]); //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::with('products')->paginate(Cts::ITEMS_PER_PAGE_PAGING);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {

        if($request->products)
        {
            $sum = 0;
            $order  = Order::create($request->all());

            //sync products in this order
            //e.g. json 'products' expected ids & quantities { "1": { "quantity": "22" },"6": { "quantity": "33" }};
            $arr_prods_and_quantities = json_decode($request->products,true);
            $order->products()->sync($arr_prods_and_quantities);
            $order->load('products');
            foreach ($order->products as $product)
            {
                $sum += $product->price;
            }
            $order->total = $sum;
            $order->save();

        }
        else
        {
            throw new Exception('Order has no products specified');
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
        if(Auth::check())
        {
            $user = Auth::user();
            $order = Order::findOrFail($id);

            //user can only see his own order, unless its admin
            if(!$user->isAdmin())
            {
                if($order->customer_id !== $user->id)
                {
                    return response('Unauthorized',Cts::HTTP_STATUS_UNAUTHORIZED);
                }
            }
            return Order::with('products')->findOrFail($id);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, $id)
    {
        $order = Order::findOrFail($id);

        if($request->products)
        {
            $sum = 0;
            $order->update($request->all());

            //sync products in this order
            //e.g. array expected should be [1=>['quantity'=>3],2=>['quantity'=>22]]
            //e.g. json 'products' expected ids & quantities { "1": { "quantity": "22" },"6": { "quantity": "33" }};
            $arr_prods_and_quantities = json_decode($request->products,true);
            $order->products()->sync($arr_prods_and_quantities);
            $order->load('products');
            foreach ($order->products as $product)
            {
                $sum += $product->price;
            }
            $order->total = $sum;
            $order->save();

        }
        else
        {
            throw new Exception('Order has no products specified');
        }

        return $order;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->destroy($id);
    }
}
