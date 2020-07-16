<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    /**
     * Get the product's Category
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * Get the product's Unit
     */
    public function unit()
    {
        return $this->belongsTo('App\Unit');
    }

}
