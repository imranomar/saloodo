<?php

namespace App\Http\Requests;

use App\Rules\ProductExistForOrders;
use Illuminate\Foundation\Http\FormRequest;


/**
 * Validation when order created or updated
 *
 */
class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return
        [
            'customer_id' => 'exists:users,id',
            'total' => 'min:0',
            'products' => [new ProductExistForOrders], //all products should exist
        ];
    }
}
