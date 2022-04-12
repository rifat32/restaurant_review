<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Restaurant;
use App\Models\ReviewNew;
use App\Models\ReviewValue;
use App\Models\Star;
use App\Models\StarTagQuestion;
use App\Models\Tag;
use App\Models\TagReview;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;

class ReviewNewController extends Controller
{
    // ##################################################
    // This method is to store variation  ReviewValue
    // ##################################################
    public function store($restaurantId, $rate, Request $request)
    {

        ReviewValue::where([
            "restaurant_id" => $restaurantId,
            "rate" => $rate
        ])
            ->delete();

        $reviewValues = $request->reviewvalue;
        $raviewValue_array = [];
        foreach ($reviewValues as $reviewValue) {
            $reviewValue["restaurant_id"] = $restaurantId;
            $reviewValue["rate"] = $rate;
            $createdReviewValue =  ReviewValue::create($reviewValue);
            array_push($raviewValue_array, $createdReviewValue);
        }

        return response($raviewValue_array, 201);
    }
    // ##################################################
    // This method is to get   ReviewValue
    // ##################################################
    public function getReviewValues($restaurantId, $rate, Request $request)
    {
        // with
        $reviewValues = ReviewValue::where([
            "restaurant_id" => $restaurantId,
            "rate" => $rate,

        ])
            ->get();


        return response($reviewValues, 200);
    }
    // ##################################################
    // This method is to get ReviewValue by id
    // ##################################################
    public function getreviewvalueById($restaurantId, Request $request)
    {
        // with
        $reviewValues = ReviewValue::where([
            "restaurant_id" => $restaurantId
        ])
            ->first();


        return response($reviewValues, 200);
    }
    // ##################################################
    // This method is to get average
    // ##################################################
    public function  getAverage($restaurantId, $start, $end, Request $request)
    {
        // with
        $reviews = ReviewNew::where([
            "restaurant_id" => $restaurantId
        ])
            ->whereBetween('created_at', [$start, $end])
            ->with("question")
            ->get();

        $data["total"]   = $reviews->count();
        $data["one"]   = 0;
        $data["two"]   = 0;
        $data["three"] = 0;
        $data["four"]  = 0;
        $data["five"]  = 0;
        foreach ($reviews as $review) {
            switch ($review->rate) {
                case 1:
                    $data[$review->question->name]["one"] += 1;
                    break;
                case 2:
                    $data["two"] += 1;
                    break;
                case 3:
                    $data["three"] += 1;
                    break;
                case 4:
                    $data["four"] += 1;
                    break;
                case 5:
                    $data[$review->question->question]["five"] += 1;
                    break;
            }
        }


        return response($data, 200);
    }
    // ##################################################
    // This method is to store   ReviewValue2
    // ##################################################
    public function store2($restaurantId, Request $request)
    {

        ReviewValue::where([
            "restaurant_id" => $restaurantId,
            "rate" => $request->rate
        ])
            ->delete();
        $reviewValue = [
            "tag" => $request->tag,
            "rate" => $request->rate,
            "restaurant_id" => $restaurantId
        ];

        $createdReviewValue =  ReviewValue::create($reviewValue);



        return response($createdReviewValue, 201);
    }
    // ##################################################
    // This method is to filter   Review
    // ##################################################
    public function  filterReview($restaurantId, $rate, $start, $end, Request $request)
    {
        // with
        $reviewValues = ReviewNew::where([
            "restaurant_id" => $restaurantId,
            "rate" => $rate
        ])
            ->with("restaurant")
            ->whereBetween('created_at', [$start, $end])
            ->get();


        return response($reviewValues, 200);
    }
    // ##################################################
    // This method is to get review by restaurant id
    // ##################################################
    public function  getReviewByRestaurantId($restaurantId, Request $request)
    {
        // with
        $reviewValue = ReviewNew::where([
            "restaurant_id" => $restaurantId,
        ])
            ->first();


        return response($reviewValue, 200);
    }
    // ##################################################
    // This method is to get customer review
    // ##################################################
    public function  getCustommerReview($restaurantId, $start, $end, Request $request)
    {
        // with
        $data["reviews"] = ReviewNew::where([
            "restaurant_id" => $restaurantId,
        ])
            ->whereBetween('created_at', [$start, $end])
            ->get();
        $data["total"]   = $data["reviews"]->count();
        $data["one"]   = 0;
        $data["two"]   = 0;
        $data["three"] = 0;
        $data["four"]  = 0;
        $data["five"]  = 0;
        foreach ($data["reviews"]  as $reviewValue) {
            switch ($reviewValue->rate) {
                case 1:
                    $data["one"] += 1;
                    break;
                case 2:
                    $data["two"] += 1;
                    break;
                case 3:
                    $data["three"] += 1;
                    break;
                case 4:
                    $data["four"] += 1;
                    break;
                case 5:
                    $data["five"] += 1;
                    break;
            }
        }

        return response($data, 200);
    }

    // ##################################################
    // This method is to store review
    // ##################################################
    public function storeReview($restaurantId,  Request $request)
    {
        foreach ($request->reviews as $singleReview) {

            $review = [
                'description' => $singleReview["description"],
                'restaurant_id' => $restaurantId,
                'rate' => $singleReview["rate"],
                'user_id' => $request->user()->id,
                'comment' => $singleReview["comment"],
                'question_id' => $singleReview["question_id"],
            ];

            $createdReview =   ReviewNew::create($review);
            foreach ($singleReview["tags"] as $tag) {
                $tag["review_id"] = $createdReview->id;
                TagReview::create($tag);
            }
        }

        return response(["message" => "created successfully"], 201);
    }
    // ##################################################
    // This method is to store question
    // ##################################################
    public function storeQuestion(Request $request)
    {

        $restaurant =    Restaurant::where(["id" => $request->restaurant_id])->first();
        if ($restaurant->enable_question == true) {
            return response()->json(["message" => "question is enabled"]);
        }
        $question = [
            'question' => $request->question,
            'restaurant_id' => $request->restaurant_id,
        ];
        if ($request->user()->hasRole("superadmin")) {
            $question["is_default"] = true;
        }


        $createdQuestion =    Question::create($question);


        return response($createdQuestion, 201);
    }
    // ##################################################
    // This method is to update question
    // ##################################################
    public function updateQuestion(Request $request)
    {
        $question = [
            'question' => $request->question,
            'restaurant_id' => $request->restaurant_id,
        ];
        $checkQuestion =    Question::where(["id" => $request->id])->first();
        if ($checkQuestion->is_default == true && !$request->user()->hasRole("superadmin")) {
            return response()->json(["message" => "you can not update the question. you are not a super admin"]);
        }
        $updatedQuestion =    tap(Question::where(["id" => $request->id]))->update(
            $question
        )
            // ->with("somthing")

            ->first();


        return response($updatedQuestion, 200);
    }
    // ##################################################
    // This method is to get question
    // ##################################################
    public function   getQuestion(Request $request)
    {
        $query =  Question::where(["restaurant_id" => $request->restaurant_id])
            ->with("tag");
        if ($request->user()->hasRole("superadmin")) {
            $query->where(["is_default" => true]);
        }
        $restaurant =    Restaurant::where(["id" => $request->restaurant_id])->first();
        if ($restaurant->enable_question == true) {
            $query->where(["is_default" => true]);
        }


        $questions =  $query->get();

        return response($questions, 200);
    }
    // ##################################################
    // This method is to get question  by id
    // ##################################################
    public function   getQuestionById($id, Request $request)
    {
        $questions =    Question::where(["id" => $id])
            ->first();
        return response($questions, 200);
    }
    // ##################################################
    // This method is to delete question by id
    // ##################################################
    public function   deleteQuestionById($id, Request $request)
    {
        $questions =    Question::where(["id" => $id])
            ->delete();
        return response(["message" => "ok"], 200);
    }
    // ##################################################
    // This method is to store tag
    // ##################################################
    public function storeTag(Request $request)
    {

        $question = [
            'tag' => $request->tag
        ];
        if ($request->user()->hasRole("superadmin")) {
            $question["is_default"] = true;
        }
        $createdQuestion =    Tag::create($question);


        return response($createdQuestion, 201);
    }
    // ##################################################
    // This method is to update tag
    // ##################################################
    public function updateTag(Request $request)
    {
        $question = [
            'tag' => $request->tag
        ];
        $checkQuestion =    Tag::where(["id" => $request->id])->first();
        if ($checkQuestion->is_default == true && !$request->user()->hasRole("superadmin")) {
            return response()->json(["message" => "you can not update the question. you are not a super admin"]);
        }
        $updatedQuestion =    tap(Tag::where(["id" => $request->id]))->update(
            $question
        )
            // ->with("somthing")

            ->first();


        return response($updatedQuestion, 200);
    }
    // ##################################################
    // This method is to get tag
    // ##################################################
    public function   getTag(Request $request)
    {
        $query =   Tag::where(["star_id" => $request->star_id]);
        if ($request->user()->hasRole("superadmin")) {
            $query->where(["is_default" => true]);
        }
        $restaurant =    Restaurant::where(["id" => $request->restaurant_id])->first();
        if ($restaurant->enable_question == true) {
            $query->where(["is_default" => true]);
        }
        $questions =  $query->get();

        return response($questions, 200);
    }
    // ##################################################
    // This method is to get tag  by id
    // ##################################################
    public function   getTagById($id, Request $request)
    {
        $questions =    Tag::where(["id" => $id])
            ->first();
        return response($questions, 200);
    }
    // ##################################################
    // This method is to delete tag by id
    // ##################################################
    public function   deleteTagById($id, Request $request)
    {
        $questions =    Tag::where(["id" => $id])
            ->delete();
        return response(["message" => "ok"], 200);
    }
    // ##################################################
    // This method is to store star
    // ##################################################
    public function storeStar(Request $request)
    {

        $question = [
            'value' => $request->value,
            'question_id' => $request->question_id,
        ];
        if ($request->user()->hasRole("superadmin")) {
            $question["is_default"] = true;
        }
        $createdQuestion =    Star::create($question);


        return response($createdQuestion, 201);
    }
    // ##################################################
    // This method is to update star
    // ##################################################
    public function updateStar(Request $request)
    {
        $question = [
            'value' => $request->value,
            'question_id' => $request->question_id,
        ];
        $checkQuestion =    Star::where(["id" => $request->id])->first();
        if ($checkQuestion->is_default == true && !$request->user()->hasRole("superadmin")) {
            return response()->json(["message" => "you can not update the question. you are not a super admin"]);
        }
        $updatedQuestion =    tap(Star::where(["id" => $request->id]))->update(
            $question
        )
            // ->with("somthing")

            ->first();


        return response($updatedQuestion, 200);
    }
    // ##################################################
    // This method is to get star
    // ##################################################
    public function   getStar(Request $request)
    {
        $query =  Star::where(["question_id" => $request->question_id]);
        if ($request->user()->hasRole("superadmin")) {
            $query->where(["is_default" => true]);
        }
        $restaurant =    Restaurant::where(["id" => $request->restaurant_id])->first();
        if ($restaurant->enable_question == true) {
            $query->where(["is_default" => true]);
        }
        $questions =  $query->get();



        return response($questions, 200);
    }
    // ##################################################
    // This method is to get star by id
    // ##################################################
    public function   getStarById($id, Request $request)
    {
        $questions =    Star::where(["id" => $id])
            ->first();
        return response($questions, 200);
    }
    public function   deleteStarById($id, Request $request)
    {
        $questions =    Star::where(["id" => $id])
            ->delete();
        return response(["message" => "ok"], 200);
    }
    // ##################################################
    // This method is to store star tag
    // ##################################################
    public function storeStarTag(Request $request)
    {

        $question = [
            'question_id' => $request->question_id,
            'tag_id' => $request->tag_id,
            'star_id' => $request->star_id,
        ];
        if ($request->user()->hasRole("superadmin")) {
            $question["is_default"] = true;
        }
        $createdQuestion =    StarTagQuestion::create($question);


        return response($createdQuestion, 201);
    }
    // ##################################################
    // This method is to update star tag
    // ##################################################
    public function updateStarTag(Request $request)
    {
        $question = [
            'question_id' => $request->question_id,
            'tag_id' => $request->tag_id,
            'star_id' => $request->star_id,
        ];
        $checkQuestion =    StarTagQuestion::where(["id" => $request->id])->first();
        if ($checkQuestion->is_default == true && !$request->user()->hasRole("superadmin")) {
            return response()->json(["message" => "you can not update the question. you are not a super admin"]);
        }
        $updatedQuestion =    tap(StarTagQuestion::where(["id" => $request->id]))->update(
            $question
        )
            // ->with("somthing")

            ->first();


        return response($updatedQuestion, 200);
    }
    // ##################################################
    // This method is to get star tag
    // ##################################################
    public function   getStarTag(Request $request)
    {
        $query =  StarTagQuestion::where(["question_id" => $request->question_id])
            ->with("question", "star", "tag");
        if ($request->user()->hasRole("superadmin")) {
            $query->where(["is_default" => true]);
        }
        $restaurant =    Restaurant::where(["id" => $request->restaurant_id])->first();
        if ($restaurant->enable_question == true) {
            $query->where(["is_default" => true]);
        }
        $questions =  $query->get();


        return response($questions, 200);
    }
    // ##################################################
    // This method is to get star tag by id
    // ##################################################
    public function   getStarTagById($id, Request $request)
    {

        $questions =    StarTagQuestion::where(["id" => $id])
            ->with("question", "star", "tag")
            ->first();
        return response($questions, 200);
    }
    // ##################################################
    // This method is to delete star tag by id
    // ##################################################
    public function   deleteStarTagById($id, Request $request)
    {
        $questions =    StarTagQuestion::where(["id" => $id])
            ->delete();
        return response(["message" => "ok"], 200);
    }
}
