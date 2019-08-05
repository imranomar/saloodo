<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    //properties that are writable
    protected $fillable = ['title','price','type','discount','discount_type'];

    protected $appends = ['price_without_discount','price_with_symbol']; //requires snake case

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bundle()
    {
        return $this->belongsToMany('App\Product', 'product_product', 'bundle_id', 'product_id');
    }

    //calculate price with discount , also converted to euros
    public function getPriceAttribute($value)
    {
        //apply discount on price
        $discount = 0;
        $discount_type = 0;

        if(isset($this->attributes['discount']))
        {
            $discount = $this->attributes['discount'];
        }

        if(isset($this->attributes['discount_type']))
        {
            $discount_type = $this->attributes['discount_type'];
        }

        //covert from cents to euros
        $price = $value / 100;

        if($discount_type ==  Cts::DISCOUNT_AMOUNT_TYPE)
        {
            $price = $price - $discount;
        }
        elseif($discount_type ==  Cts::DISCOUNT_PERCENTAGE_TYPE)
        {
            $price = ((100 - $discount)/100)*$price;
        }


        //33 to 33.00 , 33.578 to 33.58
        return number_format($price,2);
    }

    //calculate price with currency symbol
    public function getPriceWithSymbolAttribute()
    {
        return Cts::CURRENCY_SYMBOL . $this->getPriceAttribute( $this->attributes['price']);
    }

    //calculate price without discount
    public function getPriceWithoutDiscountAttribute()
    {
        return number_format($this->attributes['price']/ 100,2);
    }

    //store currency in cents
    public function setPriceAttribute($value)
    {
        //divide cents by 100 to return in euros
        $this->attributes['price'] = $value * 100;
    }

}
