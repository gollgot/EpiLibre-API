<?php


namespace App\Http\Controllers;


use App\CustomHelpers\JSONResponseHelper;
use App\PriceHistoric;
use App\Unit;
use App\User;
use Illuminate\Http\Request;

class PriceHistoricController extends Controller
{

    /**
     * Return a count of all product price change not seen yet
     * @return \Illuminate\Http\JsonResponse Json response
     */
    public function notSeenCount(){
        $JSONResponseHelper = new JSONResponseHelper();

        $notSeenPriceHistorics = PriceHistoric::where("seen", false)->get();

        return $JSONResponseHelper->successJSONResponse([
            "count" => sizeof($notSeenPriceHistorics)
        ]);
    }

}
