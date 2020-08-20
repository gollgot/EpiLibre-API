<?php


namespace App\Http\Controllers;


use App\Category;
use App\CustomHelpers\JSONResponseHelper;
use App\PriceHistoric;
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
        // Check the linked category and unit passed in parameter
        $category = Category::where("name", $request->get("category"))->first();
        $unit = Unit::where("abbreviation", $request->get("unit"))->first();

        // Bad product ID or category / unit / price (not be 0 or null)
        if(empty($product) || empty($category) || empty($unit) || empty($request->get("price"))){
            return $JSONResponseHelper->badRequestJSONResponse();
        }
        else{
            // user that has updated the product
            $user = User::where("tokenAPI", $request->bearerToken())->first();

            $oldPrice = $product->price;

            // Set new attributes
            $product->name = $request->get("name");
            $product->image = empty($request->get("image")) ? null : $request->get("image");
            $product->price = doubleval($request->get("price"));
            $product->category()->associate($category);
            $product->unit()->associate($unit);
            $product->updatedBy()->associate($user);
            $product->save();

            // Notify in DB when a price change !
            if($oldPrice != $product->price){
                $priceHistoric = new PriceHistoric();
                $priceHistoric->oldPrice = $oldPrice;
                $priceHistoric->newPrice = $product->price;
                $priceHistoric->user()->associate($user);
                $priceHistoric->product()->associate($product);
                $priceHistoric->save();
            }

            return $JSONResponseHelper->successJSONResponse($product);
        }
    }

    /**
     * Store a new product
     * @param Request $request The request
     * @return \Illuminate\Http\JsonResponse The Json response
     */
    public function store(Request $request){
        $JSONResponseHelper = new JSONResponseHelper();

        $user = User::where("tokenAPI", $request->bearerToken())->first();
        $category = Category::where("name", $request->get("category"))->first();
        $unit = Unit::where("abbreviation", $request->get("unit"))->first();

        if(empty($category) || empty($unit)){
            return $JSONResponseHelper->badRequestJSONResponse();
        }else {
            $product = new Product();

            $product->name = $request->get("name");
            $product->image = empty($request->get("image")) ? null : $request->get("image");
            $product->price = doubleval($request->get("price"));
            $product->stock = 0;
            $product->category()->associate($category);
            $product->unit()->associate($unit);
            $product->updatedBy()->associate($user);

            $product->save();

            return $JSONResponseHelper->createdJSONResponse([
                "name" => $product->name,
                "image" => $product->image,
                "price" => $product->price,
                "stock" => $product->stock,
                "category" => $product->category["name"],
                "unit" => $product->unit["abbreviation"],
                "updatedBy" => $product->updatedBy["firstname"] . " " . $product->updatedBy["lastname"]
            ]);
        }
    }


}
