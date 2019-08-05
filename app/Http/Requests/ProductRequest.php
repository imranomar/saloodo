<?php

namespace App\Http\Requests;

use App\Rules\ProductExists;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation when product is created or updated
 *
 */
class ProductRequest extends FormRequest
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
        return [
            'title' => 'required|unique:products|max:255',
            'price' => 'required|integer|min:0',
            'discount_type' => 'boolean',
            'bundle' => [new ProductExists] //all bundled products should exist
        ];
    }
}
