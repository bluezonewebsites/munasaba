<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\Followimg;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowerController extends ApiController
{
    public  function  __construct()
    {
        if(\request()->header('Authorization')){
            $this->middleware('auth:sanctum');
        }
    }

    public function getAllFollowerByUserid(Request $request)
    {
        /// متابعين ال uid
        $followers = DB::table('followers')
        ->where('followers.to_id',$request['user_id'])
        ->leftjoin('user as user_from','user_from.id','followers.user_id')
            ->leftjoin('countries', 'countries.id', 'user_from.country_id')
            ->leftjoin('regions', 'regions.id', 'user_from.region_id')
            ->leftjoin('cities', 'cities.id', 'user_from.city_id')
        ->select('followers.*',
            'user_from.name as user_name',
            'user_from.last_name as user_last_name',
            'user_from.pic as user_pic',
            'user_from.verified as user_verified',
            'countries.name_ar as countries_name_ar',
            'countries.name_en as countries_name_en',
            'cities.name_ar as cities_name_ar',
            'cities.name_en as cities_name_en',
            'regions.name_ar as regions_name_ar',
            'regions.name_en as regions_name_en'
        )->get();

        foreach ($followers as $follower){
            $follower->is_follow= Follower::where('user_id',Auth::id())
                            ->where('to_id',$follower->user_id)->first() ? 1 :0;
        }
        return $this->apiResponse($request, trans('language.message'), $followers, true);
    }



    public function getAllFollowingByUserid(Request $request)
    {
        /// متابعات ال uid
        $followings = DB::table('followers')
            ->where('followers.user_id',$request['user_id'])
            ->leftjoin('user as user_to','user_to.id','followers.to_id')
            ->leftjoin('countries', 'countries.id', 'user_to.country_id')
            ->leftjoin('regions', 'regions.id', 'user_to.region_id')
            ->leftjoin('cities', 'cities.id', 'user_to.city_id')
            ->select(
                'followers.*',
                'user_to.id as user_to_id',
                'user_to.name as user_name',
                'user_to.last_name as user_last_name',
                'user_to.pic as user_pic',
                'user_to.verified as user_verified',
                'countries.name_ar as countries_name_ar',
                'countries.name_en as countries_name_en',
                'cities.name_ar as cities_name_ar',
                'cities.name_en as cities_name_en',
                'regions.name_ar as regions_name_ar',
                'regions.name_en as regions_name_en'
            )->paginate(10);
        foreach ($followings as $follower){
            $follower->is_follow= Follower::where('to_id',$follower->to_id)
                ->where('user_id',Auth::id())->first() ? 1 :0;
        }
        return $this->apiResponse($request, trans('language.message'), $followings, true);
    }
    public function makeFollow(Request $request)
    {
        $user_id=Auth::id();
        $user= Follower::where('user_id',$user_id)->where('to_id',$request['to_id'])->first();
        if($user){
            $user->delete();
            return $this->apiResponse($request, trans('language.deleted'), null, true);

        }else{
            Follower::create([
                'user_id' => $user_id,
                'to_id' => $request['to_id'],
            ]);

            $this->save_notf('FOLLOW', $user_id
                , 'قام بمتابعتك', $user_id,$request['to_id']);
        }

        return $this->apiResponse($request, trans('language.created'), $user, true);

    }
}
