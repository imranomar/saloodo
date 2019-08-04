<?php

namespace App\Http\Requests;

use App\Rules\ProductExists;
use Illuminate\Foundation\Http\FormRequest;

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
            'products' => [new ProductExists],
        ];
    }
}
