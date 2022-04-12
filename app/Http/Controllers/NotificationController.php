<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    // ##################################################
    // This method is to store notification
    // ##################################################
    public function storeNotification($recieverId, $orderId, Request $request)
    {


        $body = $request->toArray();
        $body["reciever_id"] = $recieverId;
        $body["order_id"] = $orderId;
        $body["status"] = "unRead";


        $notification =  Notification::create($body);

        return response($notification, 200);
    }
    // ##################################################
    // This method is to update notification
    // ##################################################
    public function updateNotification($notificationId, Request $request)
    {



        $updatedVariation =    tap(Notification::where(["id" => $notificationId]))->update(
            [
                "status" => "Read",
                'message' => $request->message
            ]
        )
            // ->with("somthing")

            ->first();
        return response($updatedVariation, 200);
    }
    // ##################################################
    // This method is to get notification by reciever_id
    // ##################################################
    public function getNotification($recieverId, Request $request)
    {
        $data["content"] = Notification::where(["reciever_id" => $recieverId])->get();
        $data["total"] = $data["content"]->count();

        return response($data, 200);
    }

    // ##################################################
    // This method is to delete notification
    // ##################################################
    public function deleteNotification($notificationId, Request $request)
    {
        Notification::where([
            "id" => $notificationId,
        ])
            ->delete();



        return response(["message" => "ok"], 201);
    }
}
