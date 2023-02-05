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
        ->where('followers.uid',$request['uid'])
        ->leftjoin('user as user_from','user_from.id','followers.fid')
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
            $follower->is_follow= Follower::where('fid',Auth::id())
                            ->where('uid',$request['uid'])->first() ? 1 :0;
        }
        return $this->apiResponse($request, trans('language.message'), $followers, true);
    }
    public function makeFollow(Request $request)
    {
        $user= Follower::where('uid',$request['uid'])->where('fid',$request['anther_user_id'])->first();
        if($user){
            $user->delete();
            return $this->apiResponse($request, trans('language.deleted'), null, true);

        }else{
            Follower::create([
                'uid' => $request['uid'],
                'fid' => $request['anther_user_id'],
            ]);
        }
        $user= Followimg::where('uid',$request['uid'])->where('fid',$request['anther_user_id'])->first();
        if($user){
            $user->delete();
            return $this->apiResponse($request, trans('language.deleted'), null, true);

        }else{
            Followimg::create([
                'uid' => $request['uid'],
                'fid' => $request['anther_user_id'],
            ]);
        }
        return $this->apiResponse($request, trans('language.created'), $user, true);

    }
}
