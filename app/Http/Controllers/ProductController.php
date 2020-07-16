<?php


namespace App\Http\Controllers;


use App\CustomHelpers\JSONResponseHelper;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * Fetch all the products
     * @param Request $request The request
     * @return \Illuminate\Http\JsonResponse The Json response
     */
    public function index(Request $request){
        $JSONResponseHelper = new JSONResponseHelper();

        $products = product::orderBy('name')->with('category')->with('unit')->get();

        $resource = array();
        foreach ($products as $product){
            array_push($resource, [
                "id" => $product->id,
                "name" => $product->name,
                "price" => $product->price,
                "stock" => $product->stock,
                "image" => $product->image,
                "category" => $product->category["name"],
                "unit" => $product->unit["abbreviation"]
            ]);
        }

        return $JSONResponseHelper->successJSONResponse($resource);
    }

}
