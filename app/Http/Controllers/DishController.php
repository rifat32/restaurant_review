<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    // ##################################################
    // This method is to store dish
    // ##################################################
    public function storeDish($menuId, Request $request)
    {
        $body = $request->toArray();
        $body["menu_id"] = $menuId;

        $menu =  Dish::create($body);


        return response($menu, 200);
    }
    // ##################################################
    // This method is to update dish
    // ##################################################
    public function updateDish($dishId, Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $imageName = time() . '.' . $request->image->extension();



        $request->image->move(public_path('img/dish'), $imageName);

        $imageName = "img/dish/" . $imageName;



        $createdVariationType =    tap(Dish::where(["id" => $dishId]))->update(
            [
                "name" => $request->name,
                "price" => $request->price,
                "restaurant_id" => $request->restaurant_id,
                "image" => $imageName,

            ]
        )
            // ->with("somthing")

            ->first();


        return response($createdVariationType, 200);
    }
    // ##################################################
    // This method is to update dish image
    // ##################################################
    public function updateDishImage($dishId, Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $imageName = time() . '.' . $request->image->extension();



        $request->image->move(public_path('img/dish'), $imageName);

        $imageName = "img/dish/" . $imageName;



        $createdVariationType =    tap(Dish::where(["id" => $dishId]))->update(
            [

                "image" => $imageName,

            ]
        )
            // ->with("somthing")

            ->first();


        return response($createdVariationType, 200);
    }
    // ##################################################
    // This method is to get all dish
    // ##################################################
    public function getAllDishes($restaurantId, Request $request)
    {
        // with
        $dishVariations = Dish::where([
            "restaurant_id" => $restaurantId
        ])
            ->get();


        return response($dishVariations, 200);
    }
    // ##################################################
    // This method is to get dish by menu id
    // ##################################################
    public function getDisuBuMenuId($menuId, Request $request)
    {
        // with variation, dis_variation
        $dishVariations = Dish::where([
            "menu_id" => $menuId
        ])
            ->get();

        return response($dishVariations, 200);
    }
    // ##################################################
    // This method is to get dish by deal id
    // ##################################################
    public function getDishByDealId($dealId, Request $request)
    {
        $dishVariations = Dish::with("deal")->where([
            "id" => $dealId
        ])
            ->get();

        return response($dishVariations, 200);
    }
    // ##################################################
    // This method is to get all dish with deals
    // ##################################################
    public function getAllDishesWithDeals($dishId, Request $request)
    {
        // with variation, dis_variation
        $dishVariations = Dish::with("deal")->get();

        return response($dishVariations, 200);
    }
    // ##################################################
    // This method is to store multiple  dish
    // ##################################################
    public function storeMultipleDish($restaurantId, Request $request)
    {
        $dishes = $request->dishes;
        $dishes_array = [];
        foreach ($dishes as $dish) {
            $dish["restaurant_id"] = $restaurantId;
            $createdDish =  Dish::create($dish);
            array_push($dishes_array, $createdDish);
        }

        return response($dishes_array, 201);
    }
    // ##################################################
    // This method is to store multiple deal dish
    // ##################################################
    public function storeMultipleDealDish($menuId, Request $request)
    {
        $dishes = $request->dishes;
        $data["dishes"] = [];
        $data["deals"] = [];
        foreach ($dishes as $dish) {
            $dish["restaurant_id"] = $menuId;
            $dish["type"] = "deal";
            // @@@@ this image link should be changed
            $dish["image"] =    "https://images.unsplash.com/photo-1594315590298-329f49c8dcb9?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MXx8dGhlJTIwc3VufGVufDB8fDB8fA%3D%3D&w=1000&q=80";

            $createdDish =  Dish::create($dish);
            array_push($data["dishes"], $createdDish);
            foreach ($dish["selected"] as $selected) {
                $requestDeal = [
                    "deal_id" => $createdDish->id,
                    "dish_id" => $selected["dish_id"]
                ];
                $createdDeal = Deal::create($requestDeal);
                array_push($data["deals"], $createdDeal);
            }
        }

        return response($data, 201);
    }
    // ##################################################
    // This method is to update multiple dish
    // ##################################################
    public function updateMultipleDish(Request $request)
    {
        $dishes = $request->dishes;
        $dishes_array = [];

        foreach ($dishes as $dish) {
            $updatedDish =    tap(Dish::where(["id" => $dish["id"]]))->update(
                collect($dish)->only(['name', 'price', "take_away", "delivery", "description", "ingredients", "calories"])->all()
            )
                // ->with("somthing")
                ->first();

            array_push($dishes_array, $updatedDish);
        }




        return response($dishes_array, 200);
    }
    // ##################################################
    // This method is to update dish 2
    // ##################################################
    public function updateDish2(Request $request)
    {




        $createdVariationType =    tap(Dish::where(["id" => $request->id]))->update(
            [
                "name" => $request->name,
                "price" => $request->price,
                "description" => $request->description,
            ]
        )
            // ->with("somthing")

            ->first();


        return response($createdVariationType, 200);
    }
    // ##################################################
    // This method is to delete dish
    // ##################################################
    public function deleteDish($dishId, Request $request)
    {
        Dish::where([
            "id" => $dishId,
        ])
            ->delete();

        return response(["message" => "ok"], 200);
    }
}
