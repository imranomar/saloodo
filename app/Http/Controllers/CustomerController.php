<?php

namespace App\Http\Controllers;

use App\Cts;
use App\User;
use Auth;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function __construct()
    {
        //all need to be signed in to access all
        $this->middleware(['auth:api']);

    }

    /**
     * Get all orders of specified customer id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOrdersOfCustomer($id)
    {
        return User::where('id',$id)->findOrFail($id)->orders()->with('products')->paginate(Cts::ITEMS_PER_PAGE_PAGING);
    }

    /**
     * Get all details of specified customer id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCustomerDetails($id)
    {
        if(Auth::check())
        {
            $user = Auth::user();

            //user can see only his own details else its admin
            if($user->isAdmin() || $user->id == $id)
            {
                return User::findOrFail($id);
            }
        }

        return response('Unauthorized',Cts::HTTP_STATUS_UNAUTHORIZED);
    }
}
