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
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//---------------------------- User Routes ---------------------------
Route::post('login',[UsersController::class,'login']);
Route::post('signup',[UsersController::class,'create']);
Route::post('rate_user',[UsersController::class,'rateUser']);
Route::post('report_user',[UsersController::class,'reportUser']);
Route::post('users_search',[UsersController::class,'searchUsers']);
Route::post('user_edit',[UsersController::class,'editProfile']);

Route::post('about',[AdminController::class,'about']);
Route::post('contact_us',[AdminController::class,'contactUs']);

Route::post('users_pending',[PendingUserController::class,'pendingUser']);

Route::post('blocked',[UserBlockedController::class,'blocked']);

Route::post('fav_by_user',[FavController::class,'getAllFavByUserid']);
Route::post('fav_prod',[FavController::class,'makeFavProd']);



Route::post('followers',[FollowerController::class,'getAllFollowerByUserid']);
Route::post('following',[FollowimgController::class,'getAllFollowingByUserid']);



//---------------------------- Prods Routes ---------------------------
Route::post('prods_search',[ProdsController::class,'searchProds']);
Route::post('prods_add',[ProdsController::class,'storeProd']);
Route::post('prods_by_category',[ProdsController::class,'getAllProdsByCatid']);
Route::post('prods',[ProdsController::class,'getAllProdsByCountry']);
Route::post('prods_by_filter',[ProdsController::class,'getAllProdsByFilter']);
Route::post('prods_by_user',[ProdsController::class,'getAllProdsByUserid']);
Route::post('prods_delete',[ProdsController::class,'deleteProds']);
Route::post('comment_on_prods',[ProdsController::class,'makeCommentOnProd']);
Route::post('report_on_prods',[ProdsController::class,'makeReportOnProd']);
Route::post('like_prods',[ProdsController::class,'makeLikeOnCommentOrReplayOnProd']);
Route::post('replay_on_comment',[ProdsController::class,'makeReplayOnComment']);



//---------------------------- Categories Routes ---------------------------

Route::post('categories',[CategoriesController::class,'getAllCategories']);
Route::post('category',[CategoriesController::class,'getCategoriesById']);
Route::post('sub_category',[CategoriesController::class,'getAllSubCategories']);


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
Route::post('questions',[QuestionController::class,'getAllQuestion']);
Route::post('questions_search',[QuestionController::class,'searchQuestion']);
Route::post('questions_add',[QuestionController::class,'storeQuestion']);
Route::post('questions_delete',[QuestionController::class,'deleteQuestion']);
Route::post('comment_on_questions',[QuestionController::class,'makeCommentOnQuestion']);
Route::post('like_on_questions',[QuestionController::class,'makeLikeOnCommentOrReplayOnQuestion']);
Route::post('question_edit',[QuestionController::class,'editQuestion']);


