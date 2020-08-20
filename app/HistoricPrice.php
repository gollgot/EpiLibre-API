<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class HistoricPrice extends Model
{

    /**
     * Get the product who's concern by the price change
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    /**
     * Get the user who's changed the product price
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
