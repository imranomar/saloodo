<?php

namespace App\Rules;

use App\Product;
use Illuminate\Contracts\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as INotFoundHttpException;

class ProductExists implements Rule
{

    protected $arr_invalid_products;
    /**
     * Create a new rule instance.
     *
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
            Product::findOrFail(json_decode($value, false));
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
