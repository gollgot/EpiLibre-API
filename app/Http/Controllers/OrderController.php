<?php

namespace App\Http\Controllers;

use App\CustomHelpers\JSONResponseHelper;
use App\Order;
use App\OrderProduct;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    /**
     * Show all Orders order by date
     * @return \Illuminate\Http\JsonResponse the Json Response
     */
    public function index(){
        $JSONResponseHelper = new JSONResponseHelper();

        $orders = Order::orderBy('created_at', 'DESC')->get();

        $resource = [];
        foreach($orders as $order){
            $orderArray = [
                "totalPrice" => doubleval($order->totalPrice),
                "hasDiscount" => boolval($order->hasDiscount),
                "discountPrice" => doubleval($order->discountPrice),
                "seller" => $order->user['firstname'] . " " . $order->user["lastname"],
                "created_at" => date("d.m.Y H:i", strtotime($order->created_at)),
                "products" => $this->fetchProducts($order)
            ];
            array_push($resource, $orderArray);
        }

        return $JSONResponseHelper->successJSONResponse($resource);
    }

    /**
     * Store a new Order with all the linked orders_products, we use transaction to be sure that all data is correct
     * @param Request $request The request
     * @return \Illuminate\Http\JsonResponse The json response
     */
    public function store(Request $request){
        $JSONResponseHelper = new JSONResponseHelper();
        $user = User::where("tokenAPI", $request->bearerToken())->first();
        $totalPrice = doubleval($request->get("totalPrice"));
        $productsId = explode(";", $request->get("productsId"));
        $quantities = explode(";", $request->get("quantities"));
        $prices = explode(";", $request->get("prices"));

        // Must have a total price (not 0), some productsId, quantities and prices
        // Prices are calculate from the application (to apply it's own calculation rules)
        if(empty($totalPrice) || empty($productsId) || empty($quantities) || empty($prices)){
            return $JSONResponseHelper->badRequestJSONResponse();
        }

        try {
            // Begin the whole transaction
            DB::beginTransaction();

            // First, create the Order
            $order = new Order();
            $order->totalPrice = $totalPrice;
            // update discountPrice / hasDiscount if one exists otherwise defaut value (0 and false) will be used
            if(!empty($request->get('discountPrice'))){
                $order->discountPrice = $request->get('discountPrice');
                $order->hasDiscount = true;
            }
            $order->user()->associate($user);
            $order->save();

            // Then created all order_product
            $i = 0;
            foreach ($productsId as $productId){
                $product = Product::find($productId);

                $orderProduct = new OrderProduct();
                $orderProduct->price = $prices[$i];
                $orderProduct->quantity = $quantities[$i];
                $orderProduct->product()->associate($product);
                $orderProduct->order()->associate($order);
                $orderProduct->save();

                ++$i;
            }

            // No error, we can commit the transaction
            DB::commit();
        }catch (\Exception $e){
            // Error occured -> rollback the whole transaction and display json error bad request
            DB::rollBack();
            return $JSONResponseHelper->badRequestJSONResponse();
        }

        // no error occured
        return $JSONResponseHelper->createdJSONResponse([
            'id' => $order->id,
            'totalPrice' => doubleval($order->totalPrice),
            'hasDiscount' => boolval($order->hasDiscount),
            'discountPrice' => doubleval($order->discountPrice),
            'created_at' => date('d.m.Y H:i', strtotime($order->created_at)),
            'seller' => $order->user['firstname'] . " " . $order->user['lastname'],
            'orderProduct' => $order->ordersProducts
        ]);
    }




    /**
     * Return an array contains all Product info for a specific Order
     * @param Order $order The Order object
     * @return array An array with all Products' info
     */
    private function fetchProducts($order)
    {
        $productArray = [];
        foreach($order->ordersProducts as $orderProduct){
            array_push($productArray, [
                "name" => $orderProduct->product->name,
                "category" => $orderProduct->product->category->name,
                "unit" => $orderProduct->product->unit->abbreviation,
                "price" => $orderProduct->price,
                "quantity" => $orderProduct->quantity,
            ]);
        }

        return $productArray;
    }
}
