<?php


namespace App\Http\Controllers;


use App\CustomHelpers\JSONResponseHelper;
use App\Unit;

class UnitController extends Controller
{
    /**
     * Fetch all Units
     * @return \Illuminate\Http\JsonResponse Json response
     */
    public function index(){
        $JSONResponseHelper = new JSONResponseHelper();
        $units = Unit::orderBy('name')->get();
        return $JSONResponseHelper->successJSONResponse($units);
    }

}
