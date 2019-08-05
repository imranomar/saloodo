<?php

namespace App\Rules;

use App\Product;
use Illuminate\Contracts\Validation\Rule;

class ProductExistForOrders implements Rule
{
    /**
     * Create a new rule instance.
     *
     * Note: this is different from ProductExists validation as this expects an associate array with quantities
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try
        {
            $arr = array_keys(json_decode($value, true));
            Product::findOrFail($arr);
        }
        catch (\Exception $e)
        {
            return false;
        }

        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'One or more of the products does not exist';
    }
}
