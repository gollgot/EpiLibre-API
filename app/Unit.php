<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    /**
     * Get the products for the Unit.
     */
    public function products()
    {
        return $this->hasMany('App\Products');
    }
}
