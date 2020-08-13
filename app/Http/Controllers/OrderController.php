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
                "id" => $order->id,
                "totalPrice" => $order->totalPrice,
                "seller" => $order->user['firstname'] . " " . $order->user["lastname"],
                "created_at" => date("d.m.Y H:i", strtotime($order->created_at)),
                "nbProducts" => sizeof($order->ordersProducts)
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

        // Must have a total price (not 0), some productsId and quantities, and the same size (if we have 4 products, we must have 4 quantities)
        if(empty($totalPrice) || empty($productsId) || empty($quantities) || count($productsId) != count($quantities)){
            return $JSONResponseHelper->badRequestJSONResponse();
        }

        try {
            // Begin the whole transaction
            DB::beginTransaction();

            // First, create the Order
            $order = new Order();
            $order->totalPrice = $totalPrice;
            $order->user()->associate($user);
            $order->save();

            // Then created all order_product
            $i = 0;
            foreach ($productsId as $productId){
                $product = Product::find($productId);

                $orderProduct = new OrderProduct();
                $orderProduct->price = $product->price;
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
            'created_at' => date('d.m.Y H:i', strtotime($order->created_at)),
            'createdBy' => $order->user['firstname'] . " " . $order->user['lastname'],
            'orderProduct' => $order->ordersProducts()
        ]);
    }
}
