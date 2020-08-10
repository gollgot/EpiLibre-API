<?php


namespace App\Http\Controllers;


use App\Category;
use App\CustomHelpers\JSONResponseHelper;
use App\Product;
use App\Unit;
use App\User;
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

        $products = Product::orderBy('name')->with('category')->with('unit')->with('updatedBy')->get();

        $resource = array();
        foreach ($products as $product){
            array_push($resource, [
                "id" => $product->id,
                "name" => $product->name,
                "price" => $product->price,
                "stock" => $product->stock,
                "image" => $product->image,
                "category" => $product->category["name"],
                "unit" => $product->unit["abbreviation"],
                "updatedAt" => date('d.m.Y H:i', strtotime($product->updated_at)),
                "updatedBy" => $product->updatedBy["firstname"] . " " . $product->updatedBy["lastname"]
            ]);
        }

        return $JSONResponseHelper->successJSONResponse($resource);
    }

    /**
     * Update a whole product
     * @param Request $request The request
     * @param Integer $product_id The product id we want to update
     * @return \Illuminate\Http\JsonResponse The json response
     */
    public function update(Request $request, $product_id){
        $JSONResponseHelper = new JSONResponseHelper();
        $product = Product::find($product_id);

        // Bad product ID
        if(empty($product)){
            return $JSONResponseHelper->badRequestJSONResponse();
        }
        else{
            // Check the linked category and unit passed in parameter
            $category = Category::where("name", $request->get("category"))->first();
            $unit = Unit::where("abbreviation", $request->get("unit"))->first();
            if(empty($category) || empty($unit)){
                return $JSONResponseHelper->badRequestJSONResponse();
            }

            // user that has updated the product
            $user = User::where("tokenAPI", $request->bearerToken())->first();

            $product->name = $request->get("name");
            $product->image = empty($request->get("image")) ? null : $request->get("image");
            $product->price = $request->get("price");
            $product->category()->associate($category);
            $product->unit()->associate($unit);
            $product->updatedBy()->associate($user);

            $product->save();

            return $JSONResponseHelper->successJSONResponse($product);
        }
    }

}
