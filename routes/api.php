<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FavController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\FollowimgController;
use App\Http\Controllers\ProdsController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\UserBlockedController;
use App\Http\Controllers\UsersController;
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


Route::post('blocked',[UserBlockedController::class,'blocked']);
Route::post('users_search',[UsersController::class,'searchUsers']);
Route::post('fav',[FavController::class,'getAllFavByUserid']);
Route::post('followers',[FollowerController::class,'getAllFollowerByUserid']);
Route::post('following',[FollowimgController::class,'getAllFollowingByUserid']);



//---------------------------- Prods Routes ---------------------------
Route::post('prods_search',[ProdsController::class,'searchProds']);



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

