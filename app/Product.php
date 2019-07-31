<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    //properties that are writable
    protected $fillable = ['title','price','type'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bundle()
    {
        return $this->belongsToMany('App\Product', 'product_product', 'bundle_id', 'product_id');
    }

}
