<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FavController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\FollowimgController;
use App\Http\Controllers\PendingUserController;
use App\Http\Controllers\ProdRateController;
use App\Http\Controllers\ProdReportController;
use App\Http\Controllers\ProdsController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\UserBlockedController;
use App\Http\Controllers\UserRateController;
use App\Http\Controllers\UserReportController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\NotificationController;

use App\Models\Admin;
use App\Models\Follower;
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
prods_comment_reply
qusetion_comments
question_by_city
question_by_user_id
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//---------------------------- User Routes ---------------------------
Route::post('login',[UsersController::class,'login']);
Route::post('signup',[UsersController::class,'create']);
Route::post('resend_code',[UsersController::class,'resendCode']);
Route::post('verification',[UsersController::class,'verification']);
Route::post('change_mobile',[UsersController::class,'changeMobile']);
Route::post('change_password',[UsersController::class,'changePassword']);
Route::post('delete_profile_cover',[UsersController::class,'deleteProfileCover']);



Route::post('check-user', [UsersController::class , 'checkUser']);
Route::post('check-code', [UsersController::class , 'checkCode']);
Route::post('forgot-password', [UsersController::class , 'forgotPassword']);
Route::post('rate_user',[UsersController::class,'rateUser']);
Route::post('get_rate_user',[UsersController::class,'getRateUser']);
Route::post('profile',[UsersController::class,'profile']);
Route::post('delete_user',[UsersController::class,'delete']);





Route::post('report_user',[UsersController::class,'reportUser']);
Route::post('users_search',[UsersController::class,'searchUsers']);
Route::post('user_edit',[UsersController::class,'editProfile']);

Route::post('about',[AdminController::class,'about']);
Route::post('contact_us',[AdminController::class,'contactUs']);

Route::post('users_pending',[PendingUserController::class,'pendingUser']);

Route::post('blocked',[UserBlockedController::class,'blocked']);

Route::post('fav_by_user',[FavController::class,'getAllFavByUserid']);
Route::post('fav_prod',[FavController::class,'makeFavProd']);
Route::post('active_fuser_notification',[FavController::class,'activeNotifi']);





Route::post('followers',[FollowerController::class,'getAllFollowerByUserid']);
Route::post('make_follow',[FollowerController::class,'makeFollow']);
Route::post('following',[FollowerController::class,'getAllFollowingByUserid']);



//Route::post('following',[FollowimgController::class,'getAllFollowingByUserid']);
//Route::post('make_following',[FollowimgController::class,'makeFollowing']);



//---------------------------- Prods Routes ---------------------------
Route::post('prods_search',[ProdsController::class,'searchProds']);
Route::post('prods_add',[ProdsController::class,'storeProd']);
Route::post('prods_update',[ProdsController::class,'updateProd']);

Route::post('prods_by_category',[ProdsController::class,'getAllProdsByCatid']);
Route::post('prods_by_id',[ProdsController::class,'getAllProdsById']);
Route::post('prods',[ProdsController::class,'getAllProdsByCountry']);
Route::post('prods_by_filter',[ProdsController::class,'getAllProdsByFilter']);
Route::post('prods_by_user',[ProdsController::class,'getAllProdsByUserid']);
Route::post('prods_delete',[ProdsController::class,'deleteProds']);
Route::post('comment_on_prods',[ProdsController::class,'makeCommentOnProd']);
Route::post('report_on_prods',[ProdsController::class,'makeReportOnProd']);
Route::post('like_prods',[ProdsController::class,'makeLikeOnCommentOrReplayOnProd']);
Route::post('replay_on_comment',[ProdsController::class,'makeReplayOnComment']);
Route::post('prods_comments_replay',[ProdsController::class,'getCommentsReplayProd']);
Route::post('delete_comment_on_rates',[ProdsController::class,'deleteCommentOnRates']);


// Contract Cancel already don in service contract controller ->method destroy()
// void already done
//un paied in invoice controller->single invoice, store
// service contract , store contract bulk contract ,

//---------------------------- Categories Routes ---------------------------

Route::post('categories',[CategoriesController::class,'getAllCategories']);
Route::post('category',[CategoriesController::class,'getCategoriesById']);
Route::post('sub_category',[CategoriesController::class,'getAllSubCategories']);
Route::post('sub_category_by_id',[CategoriesController::class,'getAllSubCategoriesbyId']);


//---------------------------- Regions Routes ---------------------------

Route::post('region',[RegionsController::class,'getAllRegions']);
Route::post('region_by_city_id',[RegionsController::class,'getAllRegionsByCityId']);



//---------------------------- Countries Routes ---------------------------

Route::post('countries',[CountryController::class,'getAllCountries']);


//---------------------------- Cities Routes ---------------------------

Route::post('cities',[CitiesController::class,'getAllCities']);
Route::post('cities_by_country_id',[CitiesController::class,'getAllCitiesByCountrId']);



//---------------------------- Questions Routes ---------------------------

Route::post('question_by_user_id',[QuestionController::class,'getAllQuestionByUserid']);
Route::post('question_by_city_id',[QuestionController::class,'getAllQuestionByCityid']);
Route::post('questions',[QuestionController::class,'getAllQuestion']);
Route::post('questions_search',[QuestionController::class,'searchQuestion']);
Route::post('questions_reports',[QuestionController::class,'questionReports']);
Route::post('questions_add',[QuestionController::class,'storeQuestion']);
Route::post('questions_delete',[QuestionController::class,'deleteQuestion']);
Route::post('comment_on_questions',[QuestionController::class,'makeCommentOnQuestion']);
Route::post('comment_reports',[QuestionController::class,'commentReports']);
Route::post('reply_reports',[QuestionController::class,'replyReports']);
Route::post('delete_comment_on_questions',[QuestionController::class,'deleteCommentOnQuestions']);
Route::post('like_on_questions',[QuestionController::class,'makeLikeOnCommentOrReplayOnQuestion']);
Route::post('question_edit',[QuestionController::class,'editQuestion']);
Route::post('question_comments',[QuestionController::class,'getQuestionsComments']);
Route::post('question_comments_replay',[QuestionController::class,'getCommentsReplayQuest']);


//---------------------------- Chat Routes ---------------------------

############# Room ##################
Route::post('get_rooms',[RoomController::class,'getRooms']);
Route::post('create_room',[RoomController::class,'store']);
Route::post('destroy_room',[RoomController::class,'destroy']);
Route::post('del_multi_room',[RoomController::class,'destroyAll']);
Route::post('block_room',[RoomController::class,'blockRoom']);
Route::post('report_room',[RoomController::class,'reportRoom']);
############# Chat ##################
Route::post('chat_by_room',[ChatController::class,'chatByRoom']);
Route::post('send_message',[ChatController::class,'store']);


///////////////////////////////// start notifications //////////////////////////////////////////
Route::post('notifications', [NotificationController::class, 'index']);
Route::post('notifications/save_token' , [NotificationController::class , 'save_token']);
Route::post('notifications/count' , [NotificationController::class , 'count']);
Route::post('notifications/show' , [NotificationController::class , 'show']);
Route::post('notifications/active' , [NotificationController::class , 'active']);
Route::post('notifications/delete' , [NotificationController::class , 'delete']);
Route::post('notifications/delete_all' , [NotificationController::class , 'deleteAll']);

///////////////////////////////// end notifications ///////////////////////////////////////////////





