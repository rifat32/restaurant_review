<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderVariation;
use App\Models\RestaurantTable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // ##################################################
    // This method is to store order
    // ##################################################
    public function store($restaurantId, Request $request)
    {

        $insertableOrderData = [
            "amount" => $request->amount,
            "order_by" => $request->user()->id,
            "table_number" => $request->table_number,
            "customer_name" => $request->customer_name,
            "remarks" => $request->remarks,
            "type" => $request->type,
            "restaurant_id" => $restaurantId,
            "status" => "pending",
            "customer_id" => $request->user()->id
        ];
        $insertedOrder =  Order::create($insertableOrderData);

        if ($request->type == "Delivery") {

            if (!$request->user()->id) {
                $insertableUser = [
                    'first_Name' => $request->customer_name,
                    'email' => $request->phone . Str::random(5) . "@gmail.com",
                    'password',
                    'phone' => $request->phone,
                    'type' => "guest user"
                ];
                $user =   User::create($insertableUser);
                $insertedOrder->customer_id = $user->id;
                $insertedOrder->save();
            }
        }
        RestaurantTable::create([
            "restaurant_id" => $restaurantId,
            "status" => "Booked",
            "table_no" => $request->table_number,
            "order_id" => $insertedOrder->id,
        ]);
        foreach ($request->dishes as $dish) {
            OrderDetail::create([
                "type" => "take away",
                "qty" => $dish["qty"],
                "order_id" => $insertedOrder->id,
                "dish_id" => $dish["id"],
            ]);
            foreach ($dish["variation"] as $variation) {
                OrderVariation::create([
                    "dish_id" => $dish["id"],
                    "order_id" => $insertedOrder->id,
                    "dish_id" => $dish["id"],
                    "variation_id" => $variation["id"],
                ]);
            }
        }

        return response()->json([
            "message" => "order inserted"
        ]);
    }
    // ##################################################
    // This method is to store order by user
    // ##################################################
    public function storeByUser($restaurantId, Request $request)
    {
        $insertableOrderData = [
            "amount" => $request->amount,
            "order_by" => $request->user()->id,
            "table_number" => $request->table_number,
            "customer_name" => $request->customer_name,
            "remarks" => $request->remarks,
            "type" => $request->type,
            "restaurant_id" => $restaurantId,
            "status" => "pending",
        ];
        $insertedOrder =  Order::create($insertableOrderData);


        RestaurantTable::create([
            "restaurant_id" => $restaurantId,
            "status" => "Booked",
            "table_no" => $request->table_number,
            "order_id" => $insertedOrder->id,
        ]);
        foreach ($request->dishes as $dish) {
            OrderDetail::create([
                "type" => "take away",
                "qty" => $dish["qty"],
                "order_id" => $insertedOrder->id,
                "dish_id" => $dish["id"],
            ]);
            foreach ($dish["variation"] as $variation) {
                OrderVariation::create([
                    "dish_id" => $dish["id"],
                    "order_id" => $insertedOrder->id,
                    "dish_id" => $dish["id"],
                    "variation_id" => $variation["id"],
                ]);
            }
        }

        return response()->json([
            "message" => "order inserted"
        ]);
    }
    // ##################################################
    // This method is to complete order
    // ##################################################
    public function     orderComplete($orderId, Request $request)
    {



        $updatedOrder =    tap(Order::where(["id" => $orderId]))->update(
            [
                "status" => "completed",
                "card" => $request->card,
                "cash" => $request->cash,
            ]
        )
            // ->with("somthing")

            ->first();
        RestaurantTable::where([
            "order_id" => $orderId
        ])
            ->delete();
        return response($updatedOrder, 200);
    }
    // ##################################################
    // This method is to update order status
    // ##################################################
    public function     updateStatus($orderId, Request $request)
    {

        $updatedOrder =    tap(Order::where(["id" => $orderId]))->update(
            [
                "status" => $request->status,
            ]
        )
            // ->with("somthing")

            ->first();

        return response($updatedOrder, 200);
    }
    // ##################################################
    // This method is to edit order
    // ##################################################
    public function     editOrder($orderId, Request $request)
    {
        RestaurantTable::where([
            "order_id" => $orderId
        ])
            ->delete();
        OrderVariation::where([
            "order_id" => $orderId
        ])
            ->delete();
        OrderDetail::where([
            "order_id" => $orderId
        ])
            ->delete();
        $updatableData = [
            "amount" => $request->amount,
            "table_number" => $request->table_number,
            "remarks" => $request->remarks,
        ];
        $updatedOrder =    tap(Order::where(["id" => $orderId]))->update(
            $updatableData
        )
            ->first();



        RestaurantTable::create([
            "restaurant_id" => $updatedOrder->restaurant_id,
            "status" => "Booked",
            "table_no" => $request->table_number,
            "order_id" => $updatedOrder->id,
        ]);
        foreach ($request->dishes as $dish) {
            OrderDetail::create([
                "type" => "take away",
                "qty" => $dish["qty"],
                "order_id" => $updatedOrder->id,
                "dish_id" => $dish["id"],
            ]);
            foreach ($dish["variation"] as $variation) {
                OrderVariation::create([
                    "dish_id" => $dish["id"],
                    "order_id" => $updatedOrder->id,
                    "dish_id" => $dish["id"],
                    "variation_id" => $variation["id"],
                ]);
            }
        }

        return response()->json([
            "message" => "order updated"
        ]);
    }
    // ##################################################
    // This method is to delete order
    // ##################################################
    public function     deleteOrder($orderId)
    {
        RestaurantTable::where([
            "order_id" => $orderId
        ])
            ->delete();
        OrderVariation::where([
            "order_id" => $orderId
        ])
            ->delete();
        OrderDetail::where([
            "order_id" => $orderId
        ])
            ->delete();

        Order::where(["id" => $orderId])
            ->delete();





        return response()->json([
            "message" => "order deleted"
        ]);
    }
    // ##################################################
    // This method is to get order by id
    // ##################################################
    public function     getOrderById($orderId)
    {
        $orders = Order::with("restaurant", "detail.dish", "ordervariation.variation", "user")
            ->where(["id" => $orderId])
            ->first();

        return response()->json($orders);
    }
    // ##################################################
    // This method is to get order by customer id
    // ##################################################
    public function     getOrderByCustomerId($customerId)
    {
        $orders = Order::with("restaurant", "detail.dish", "ordervariation.variation", "user")
            ->where(["customer_id" => $customerId])
            ->get();

        return response()->json($orders);
    }
    // ##################################################
    // This method is to get todays order by status
    // ##################################################
    public function     getTodaysOrderByStatus($status)
    {
        $orders = Order::with("restaurant", "detail.dish", "ordervariation.variation", "user")
            ->where([

                "status" => $status
            ])
            ->where("created_at", ">=", Carbon::today())
            ->get();

        return response()->json($orders);
    }
    // ##################################################
    // This method is to get all order
    // ##################################################
    public function     getAllOrder()
    {
        $orders = Order::with("restaurant", "detail.dish", "ordervariation.variation", "user")
            ->latest()
            ->get();

        return response()->json($orders);
    }
    // ##################################################
    // This method is to get all pending order
    // ##################################################
    public function     getAllPendingOrder($restaurantId)
    {
        $orders = Order::with("restaurant", "detail.dish", "ordervariation.variation", "user")
            ->where([
                "restaurant_id" => $restaurantId,
                "status" => "pending"
            ])
            ->latest()
            ->get();

        return response()->json($orders);
    }
    // ##################################################
    // This method is to get all autoprint order
    // ##################################################
    public function     getAllAutoPrintOrder($restaurantId)
    {
        $orders = Order::with("restaurant", "detail.dish", "ordervariation.variation", "user")
            ->where([
                "restaurant_id" => $restaurantId,
                "autoprint" => false
            ])
            ->latest()
            ->get();

        return response()->json($orders);
    }
    // ##################################################
    // This method is to get daily order report
    // ##################################################
    public function     getdailyOrderReport()
    {
        $orders = Order::with("restaurant", "detail.dish", "ordervariation.variation", "user")
            ->where("created_at", ">=", Carbon::today())
            ->get();

        $data["Delivery"] = 0;
        $data["DineIn"] = 0;
        $data["Takeaway"] = 0;
        foreach ($orders as $order) {
            if ($order->type == "Delivery") {
                $data["Delivery"] += 1;
            }
            if ($order->type == "DineIn") {
                $data["DineIn"] += 1;
            }
            if ($order->type == "Takeaway") {
                $data["Takeaway"] += 1;
            }
        }

        return response()->json($data);
    }




    // ##################################################
    // This method is to get order report
    // ##################################################
    public function     getOrderReport($min, $max, $fromdate, $todate, $status)
    {
        $orders = Order::with("restaurant", "detail.dish", "ordervariation.variation", "user")
            ->whereBetween("amount", [$min, $max])
            ->whereBetween("created_at", [$fromdate, $todate])
            ->where("status", $status)
            ->latest()
            ->get();

        return response()->json($orders);
    }
    // ##################################################
    // This method is to get order report by restaurant id
    // ##################################################
    public function     getorderReportByRestaurantId($restaurantId, $min, $max, $fromdate, $todate, $status)
    {
        $orders = Order::with("restaurant", "detail.dish", "ordervariation.variation", "user")
            ->whereBetween("amount", [$min, $max])
            ->whereBetween("created_at", [$fromdate, $todate])
            ->where("status", $status)
            ->where("restaurant_id", $restaurantId)
            ->latest()
            ->get();

        return response()->json($orders);
    }
    // ##################################################
    // This method is to get order by user
    // ##################################################
    public function     getOrderByUser(Request $request)
    {
        $orders = Order::with("restaurant", "detail.dish", "ordervariation.variation", "user")
            ->where(["customer_id" => $request->user()->id])
            ->get();

        return response()->json($orders);
    }
}
