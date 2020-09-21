<?php


namespace App\Http\Controllers;


use App\CustomHelpers\JSONResponseHelper;
use App\HistoricPrice;
use App\Unit;
use App\User;
use Illuminate\Http\Request;

class HistoricPriceController extends Controller
{

    /**
     * Fetch all historic prices order by date desc
     * @return \Illuminate\Http\JsonResponse The JsonResponse
     */
    public function index(){
        $JSONResponseHelper = new JSONResponseHelper();

        $historicPrices = HistoricPrice::with('user')
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        $resource = [];
        foreach($historicPrices as $historicPrice){
            array_push($resource, [
                "productName" => $historicPrice->product['name'],
                "oldPrice" => $historicPrice->oldPrice,
                "newPrice" => $historicPrice->newPrice,
                "seen" => $historicPrice->seen,
                "createdAt" => date('d.m.Y H:i', strtotime($historicPrice->created_at)),
                "createdBy" => $historicPrice->user['firstname'] . " " . $historicPrice->user['lastname']
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

        $notSeenHistoricPrices = HistoricPrice::where("seen", false)->get();

        return $JSONResponseHelper->successJSONResponse([
            "count" => sizeof($notSeenHistoricPrices)
        ]);
    }

    /**
     * Toggle all historic prices that his not seen yet to seen
     * @return \Illuminate\Http\JsonResponse The Json response
     */
    public function toggleSeen(){
        $JSONResponseHelper = new JSONResponseHelper();

        $historicPricesNotSeen = HistoricPrice::where("seen", false)->get();

        foreach($historicPricesNotSeen as $historicPriceNotSeen){
            $historicPriceNotSeen->seen = true;
            $historicPriceNotSeen->save();
        }

        return $JSONResponseHelper->successJSONResponse($historicPricesNotSeen);
    }

}
