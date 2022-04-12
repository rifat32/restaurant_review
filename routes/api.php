<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DailyViewsController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewNewController;
use App\Http\Controllers\VariationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




// Auth Route login user
Route::post('/auth', [AuthController::class, "login"]);
Route::post('/auth/register', [AuthController::class, "register"]);
Route::post('/owner/super/admin', [OwnerController::class, "createsuperAdmin"]);
Route::post('/owner', [OwnerController::class, "createUser"]);
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
// @@@@@@@@@@@@@@@@@@@@  Protected Routes      @@@@@@@@@@@@@@@@@
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
Route::middleware(['auth:api'])->group(function () {

    // #################
    // Auth Routes
    // #################
    Route::post('/auth/checkpin/{id}', [AuthController::class, "checkPin"]);
    Route::get('/auth', [AuthController::class, "getUserWithRestaurent"]);
    // #################
    // Owner Routes
    // Authorization may be hide for some routes I do not know
    // #################
    Route::get('/owner/role/getrole', [OwnerController::class, "getRole"]);
    Route::post('/owner/user/registration', [OwnerController::class, "createUser2"]);
    // guest user
    Route::post('/owner/guestuserregister', [OwnerController::class, "createGuestUser"]);
    // end of guest user
    Route::post('/owner/staffregister/{restaurantId}', [OwnerController::class, "createStaffUser"]);

    Route::post('/owner/pin/{ownerId}', [OwnerController::class, "updatePin"]);

    Route::get('/owner/{ownerId}', [OwnerController::class, "getOwnerById"]);
    Route::get('/owner/getAllowner/withourrestaurant', [OwnerController::class, "getOwnerNotHaveRestaurent"]);
    Route::get('/owner/loaduser/bynumber/{phoneNumber}', [OwnerController::class, "getOwnerByPhoneNumber"]);

    Route::patch('/owner/updateuser/{userId}', [OwnerController::class, "updateUser"]);
    Route::patch('/owner/profileimage/{userId}', [OwnerController::class, "updateImage"]);


    // #################
    // dailyviews Routes

    // #################
    Route::post('/dailyviews/{restaurantId}', [DailyViewsController::class, "store"]);
    Route::patch('/dailyviews/update/{restaurantId}', [DailyViewsController::class, "update"]);


    // #################
    // forggor password Routes

    // #################

    Route::patch('/forgetpassword/changepassword', [ForgotPasswordController::class, "changePassword"]);

    // #################
    // notification  Routes

    // #################

    Route::post('/notification/{recieverId}/{orderId}', [NotificationController::class, "storeNotification"]);
    Route::patch('/notification/{notificationId}', [NotificationController::class, "updateNotification"]);
    Route::get('/notification/{recieverId}', [NotificationController::class, "getNotification"]);
    Route::delete('/notification/{notificationId}', [NotificationController::class, "deleteNotification"]);


    // #################
    // review  Routes

    // #################
    Route::post('/review/reviewvalue/{restaurantId}/{rate}', [ReviewController::class, "store"]);
    Route::get('/review/getvalues/{restaurantId}/{rate}', [ReviewController::class, "getReviewValues"]);
    Route::get('/review/getvalues/{restaurantId}', [ReviewController::class, "getreviewvalueById"]);
    Route::get('/review/getavg/review/{restaurantId}/{start}/{end}', [ReviewController::class, "getAverage"]);
    Route::post('/review/addupdatetags/{restaurantId}', [ReviewController::class, "store2"]);

    Route::get('/review/getreview/{restaurantId}/{rate}/{start}/{end}', [ReviewController::class, "filterReview"]);
    Route::get('/review/getreviewAll/{restaurantId}', [ReviewController::class, "getReviewByRestaurantId"]);
    Route::get('/review/getcustomerreview/{restaurantId}/{start}/{end}', [ReviewController::class, "getCustommerReview"]);
    Route::post('/review/{restaurantId}', [ReviewController::class, "storeReview"]);
    // #################
    // review new  Routes

    // #################
    Route::post('/review-new/reviewvalue/{restaurantId}/{rate}', [ReviewNewController::class, "store"]);
    Route::get('/review-new/getvalues/{restaurantId}/{rate}', [ReviewNewController::class, "getReviewValues"]);
    Route::get('/review-new/getvalues/{restaurantId}', [ReviewNewController::class, "getreviewvalueById"]);
    Route::get('/review-new/getavg/review/{restaurantId}/{start}/{end}', [ReviewNewController::class, "getAverage"]);
    Route::post('/review-new/addupdatetags/{restaurantId}', [ReviewNewController::class, "store2"]);

    Route::get('/review-new/getreview/{restaurantId}/{rate}/{start}/{end}', [ReviewNewController::class, "filterReview"]);
    Route::get('/review-new/getreviewAll/{restaurantId}', [ReviewNewController::class, "getReviewByRestaurantId"]);
    Route::get('/review-new/getcustomerreview/{restaurantId}/{start}/{end}', [ReviewNewController::class, "getCustommerReview"]);
    Route::post('/review-new/{restaurantId}', [ReviewNewController::class, "storeReview"]);
    // #################
    // question  Routes
    // #################
    Route::post('/review-new/create/questions', [ReviewNewController::class, "storeQuestion"]);
    Route::put('/review-new/update/questions', [ReviewNewController::class, "updateQuestion"]);
    Route::get('/review-new/get/questions', [ReviewNewController::class, "getQuestion"]);
    Route::get('/review-new/get/questions/{id}', [ReviewNewController::class, "getQuestionById"]);
    Route::delete('/review-new/delete/questions/{id}', [ReviewNewController::class, "deleteQuestionById"]);
    // #################
    // tag  Routes
    // #################
    Route::post('/review-new/create/tags', [ReviewNewController::class, "storeTag"]);
    Route::put('/review-new/update/tags', [ReviewNewController::class, "updateTag"]);
    Route::get('/review-new/get/tags', [ReviewNewController::class, "getTag"]);
    Route::get('/review-new/get/tags/{id}', [ReviewNewController::class, "getTagById"]);
    Route::delete('/review-new/delete/tags/{id}', [ReviewNewController::class, "deleteTagById"]);
    // #################
    // Star Routes
    // #################
    Route::post('/review-new/create/stars', [ReviewNewController::class, "storeStar"]);
    Route::put('/review-new/update/stars', [ReviewNewController::class, "updateStar"]);
    Route::get('/review-new/get/stars', [ReviewNewController::class, "getStar"]);
    Route::get('/review-new/get/stars/{id}', [ReviewNewController::class, "getStarById"]);
    Route::delete('/review-new/delete/stars/{id}', [ReviewNewController::class, "deleteStarById"]);
    // #################
    // Star tag question Routes
    // #################
    Route::post('/star-tag-question', [ReviewNewController::class, "storeStarTag"]);
    Route::put('/star-tag-question', [ReviewNewController::class, "updateStarTag"]);
    Route::get('/star-tag-question', [ReviewNewController::class, "getStarTag"]);
    Route::get('/star-tag-question/{id}', [ReviewNewController::class, "getStarTagById"]);
    Route::delete('/star-tag-question/{id}', [ReviewNewController::class, "deleteStarTagById"]);
});
// #################
// forggor password Routes

// #################

Route::post('/forgetpassword', [ForgotPasswordController::class, "store"]);
Route::patch('/forgetpassword/reset/{token}', [ForgotPasswordController::class, "changePasswordByToken"]);
