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
     * Fetch all price historics order by date desc
     * @return \Illuminate\Http\JsonResponse The JsonResponse
     */
    public function index(){
        $JSONResponseHelper = new JSONResponseHelper();

        $priceHistorics = PriceHistoric::with('user')
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        $resource = [];
        foreach($priceHistorics as $priceHistoric){
            array_push($resource, [
                "productName" => $priceHistoric->product['name'],
                "oldPrice" => $priceHistoric->oldPrice,
                "newPrice" => $priceHistoric->newPrice,
                "seed" => $priceHistoric->seen,
                "createdAt" => $priceHistoric->created_at,
                "createdBy" => $priceHistoric->user['firstname'] . " " . $priceHistoric->user['lastname']
            ]);
        }

        return $JSONResponseHelper->successJSONResponse($resource);
    }

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
