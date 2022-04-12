<?php

namespace App\Http\Controllers;

use App\Models\DailyView;
use Illuminate\Http\Request;

class DailyViewsController extends Controller
{
    // ##################################################
    // This method is to store daily views
    // ##################################################
    public function store($restaurantId, Request $request)
    {

        $body = $request->toArray();
        $body["restaurant_id"] = $restaurantId;

        $View  =  DailyView::create($body);

        return response($View, 200);
    }
    // ##################################################
    // This method is to update daily views
    // ##################################################
    public function update($restaurantId, Request $request)
    {

        $query = DailyView::where(["restaurant_id" => $restaurantId]);
        $view = $query->first();
        if ($view) {
            $updatedView =    tap($query)->update(
                [
                    "view_date" => $request->view_date,
                    "daily_views" => $view->daily_views + 1
                ]
            )->first();
            return response($updatedView, 200);
        }
        return response("no view found", 200);
    }
}
