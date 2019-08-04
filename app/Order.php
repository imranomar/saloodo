<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id','delivered','total'];

    protected $appends = ['total_with_symbol'];

    public function products()
    {
        return $this->belongsToMany('\App\Product')->withPivot('quantity');
    }

//    public function getTotalAttribute()
//    {
//        $sum = 0;
//        $products = $this->products;
//        foreach ($products as $product)
//        {
//            $sum += $product->price;
//        }
//        return $sum;
//    }
//

    public function getTotalAttribute($value)
    {
        return $value / 100;
    }

    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = $value * 100;
    }

    public function getTotalWithSymbolAttribute()
    {
        return Cts::CURRENCY_SYMBOL . $this->total;
    }



}
