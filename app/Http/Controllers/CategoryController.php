<?php


namespace App\Http\Controllers;


use App\Category;
use App\CustomHelpers\JSONResponseHelper;

class CategoryController extends Controller
{
    /**
     * Fetch all Categories
     * @return \Illuminate\Http\JsonResponse Json response
     */
    public function index(){
        $JSONResponseHelper = new JSONResponseHelper();
        $products = Category::orderBy('name')->get();
        return $JSONResponseHelper->successJSONResponse($products);
    }

}
