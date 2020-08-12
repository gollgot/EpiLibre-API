<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    public $table = 'orders_products';

    /**
     * Get the Order of the OrderProduct
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    /**
     * Get the Product of the OrderProduct
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
