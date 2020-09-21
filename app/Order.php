<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * Get the orders_products in the Order
     */
    public function ordersProducts()
    {
        return $this->hasMany('App\OrderProduct');
    }

    /**
     * Get the author of the Order
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
